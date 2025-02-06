import requests
from bs4 import BeautifulSoup, NavigableString, Tag

# =========================== 1) FinStat – získavanie IČO ===========================

def scrape_finstat_last_6_pages() -> list[str]:
    """
    Prejde posledných 6 strán FinStatu zoradených podľa creation-date-desc a
    extrahuje IČO z každej firmy.
    """
    base_url = "https://finstat.sk/databaza-firiem-organizacii"
    headers = {
        "User-Agent": (
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
            "(KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36"
        )
    }
    all_icos = []

    for page in range(1, 7):
        url = f"{base_url}?Sort=creation-date-desc&page={page}"
        print(f"[DEBUG] Sťahujem FinStat stranu {page}: {url}")
        resp = requests.get(url, headers=headers)
        resp.raise_for_status()
        html = resp.text
        snippet = html[:500].replace("\n", " ")
        print("[DEBUG] Prvých 500 znakov HTML:", snippet, "...")

        soup = BeautifulSoup(html, "html.parser")
        table = soup.select_one("table.data-table-main")
        if not table:
            print("[WARNING] Nenašiel som tabuľku 'data-table-main'. Možno captcha alebo zmena HTML.")
            continue

        rows = table.select("tbody tr")
        print(f"[DEBUG] Našiel som {len(rows)} riadkov v tabuľke FinStatu.")
        for row in rows:
            spans = row.select("span.clr-gray")
            if len(spans) >= 2:
                # Predpokladáme, že druhý <span> obsahuje IČO
                ico_text = spans[-1].get_text(strip=True)
                if ico_text.isdigit():
                    all_icos.append(ico_text)
            else:
                continue

    print(f"[DEBUG] Spolu získaných IČO z FinStatu: {len(all_icos)}")
    return all_icos

# =========================== 2) ORSR – vyhľadávanie a získavanie URL aktuálneho výpisu ===========================

def search_orsr_by_ico(ico: str) -> str:
    base_url = "https://www.orsr.sk/hladaj_ico.asp"
    full_url = f"{base_url}?ICO={ico}"
    print(f"[DEBUG] Vyhľadávam IČO={ico} na adrese: {full_url}")

    resp = requests.get(full_url)
    resp.raise_for_status()
    html = resp.content.decode("windows-1250", errors="replace")
    return html

def parse_orsr_search_result(html: str) -> str:
    soup = BeautifulSoup(html, "html.parser")
    a_tag = soup.find("a", href=lambda href: href and "vypis.asp" in href,
                       alt="Aktuálny výpis", title="Aktuálny výpis")
    if not a_tag:
        a_tag = soup.find("a", href=lambda href: href and "vypis.asp" in href)
    if a_tag:
        href = a_tag["href"].strip()
        if href.startswith("/"):
            href = "https://www.orsr.sk" + href
        else:
            href = "https://www.orsr.sk/" + href
        return href
    else:
        return None

def scrape_orsr_detail_url_by_ico(ico: str) -> str:
    search_html = search_orsr_by_ico(ico)
    vypis_link = parse_orsr_search_result(search_html)
    return vypis_link

# =========================== 3) Extrakcia údajov o konateľoch z detailu ===========================

def extract_lines_from_td(td: Tag) -> list[str]:
    lines = []
    current_line = ""
    for child in td.children:
        if isinstance(child, Tag) and child.name == "br":
            if current_line.strip():
                lines.append(current_line.strip())
            current_line = ""
        else:
            if isinstance(child, Tag):
                current_line += child.get_text(" ", strip=True) + " "
            elif isinstance(child, NavigableString):
                current_line += child.strip() + " "
    if current_line.strip():
        lines.append(current_line.strip())
    return lines

def parse_konatelia_from_detail(html: str) -> list[dict]:
    soup = BeautifulSoup(html, "html.parser")
    konatelia = []

    statutarny_span = soup.find('span', class_='tl', string=lambda text: text and "Štatutárny orgán" in text)
    if not statutarny_span:
        print("[WARNING] Sekcia 'Štatutárny orgán' nebola nájdená.")
        return konatelia

    parent_td = statutarny_span.find_next('td')
    if not parent_td:
        print("[WARNING] Sekcia 'Štatutárny orgán' nemá očakávanú štruktúru (chýba <td>).")
        return konatelia

    inner_tables = parent_td.find_all('table')
    for table in inner_tables:
        td_info = table.find('td', width=lambda w: w and w.strip() == "67%")
        if not td_info:
            continue

        lines = extract_lines_from_td(td_info)
        if len(lines) < 3:
            continue

        konatelia.append({
            'meno': lines[0],
            'ulica': lines[1],
            'psc_mesto': lines[2]
        })
    return konatelia

# =========================== 4) Ukladanie do databázy cez Laravel API ===========================

def store_konatelia_to_api(konatelia_data: list[dict]):
    """
    Odošle POST request na váš Laravel API endpoint, ktorý uloží údaje o konateľoch.
    Predpokladáme, že endpoint je dostupný na napr. http://127.0.0.1:8001/api/konatelia.
    """
    api_url = "http://127.0.0.1:8001/api/konatelia"
    headers = {"Content-Type": "application/json"}
    payload = {"konatelia": konatelia_data}

    print(f"[DEBUG] Odosielam {len(konatelia_data)} záznamov o konateľoch do API: {api_url}")
    response = None
    try:
        response = requests.post(api_url, json=payload, headers=headers)
        response.raise_for_status()
        print("[DEBUG] Údaje o konateľoch boli úspešne uložené do databázy.")
    except requests.RequestException as e:
        print("[ERROR] Chyba pri odosielaní údajov do API:", e)
        if response is not None:
            print("Response:", response.text)


# =========================== 5) HLAVNÝ PROGRAM ===========================

def main():
    print("=== Začíname: Zo FinStatu získam IČO, následne z ORSR extrahujem URL aktuálneho výpisu a údaje o konateľoch ===\n")

    finstat_icos = scrape_finstat_last_6_pages()
    if not finstat_icos:
        print("[WARNING] Z FinStatu sa nezískali žiadne IČO – môže ísť o captcha alebo zmenenú štruktúru.")
        return

    print(f"\n=== Načítaných IČO z FinStatu: {len(finstat_icos)} ===\n")

    all_konatelia = []

    for ico in finstat_icos:
        print(f"\n[INFO] Spracúvam IČO={ico} – vyhľadávam v ORSR...")
        vypis_link = scrape_orsr_detail_url_by_ico(ico)
        if not vypis_link:
            print(f" - Pre IČO {ico} nebol nájdený odkaz na aktuálny výpis.")
            continue

        print(f" - Aktuálny výpis pre IČO {ico}: {vypis_link}")

        detail_resp = requests.get(vypis_link)
        detail_resp.raise_for_status()
        detail_resp.encoding = "windows-1250"
        detail_html = detail_resp.text

        konatelia = parse_konatelia_from_detail(detail_html)
        if konatelia:
            print("   Konatelia:")
            for osoba in konatelia:
                print(f"     ICO: {ico}")
                print(f"     Meno: {osoba['meno']}")
                print(f"     Ulica a číslo: {osoba['ulica']}")
                print(f"     PSČ a mesto: {osoba['psc_mesto']}")
                print("     ------------------------------")

                all_konatelia.append(osoba)
        else:
            print("   [INFO] Pre túto firmu neboli zistené údaje o konateľoch.")

    if all_konatelia:
        store_konatelia_to_api(all_konatelia)
    else:
        print("[INFO] Neboli nájdené žiadne údaje o konateľoch na uloženie.")

    print("\n=== HOTOVO ===")

if __name__ == "__main__":
    main()
