import requests
from bs4 import BeautifulSoup
from datetime import datetime
import pymysql

target_date_str = '2024-10-16'
target_date = datetime.strptime(target_date_str, '%Y-%m-%d')

base_url = 'https://finstat.sk/databaza-firiem-organizacii?Sort=creation-date-desc&page={}'

db_connection = pymysql.connect(
    host='mysql80.r6.websupport.sk',
    user='ta6difry',
    password='Ye4@[OJqs4',
    database='companies'
)

cursor = db_connection.cursor()

# Funkcia na uloženie dát do databázy
def save_to_database(id_value, url, address, creation_date):
    sql = "INSERT INTO finstat_data (id, url, address, creation_date) VALUES (%s, %s, %s, %s)"
    values = (id_value, url, address, creation_date)
    cursor.execute(sql, values)
    db_connection.commit()

# Funkcia na získanie URL adries a dátumov vytvorenia z každej stránky
def get_finstat_urls_with_dates(page_num):
    url = base_url.format(page_num)
    response = requests.get(url)

    if response.status_code == 200:
        soup = BeautifulSoup(response.content, 'html.parser')
        rows = soup.find_all('tr')

        # Inicializácia zoznamu na ukladanie dát
        page_data = []

        for row in rows:
            link_tag = row.find('a', class_='truncate openwindow')
            if link_tag and 'href' in link_tag.attrs:
                href = link_tag['href']
                id_value = href.split('/')[-1]  # Použi id namiesto ico

                date_text = row.find_all('td')[-1].text.strip()
                try:
                    creation_date = datetime.strptime(date_text, '%d.%m.%Y')
                except ValueError:
                    creation_date = None

                if creation_date and creation_date == target_date:
                    finstat_url = f"https://finstat.sk/{id_value}"  # Tu môžeš použiť aj id_value
                    page_data.append((id_value, finstat_url, creation_date))

        return page_data
    else:
        return []

# Funkcia na extrakciu adresy (Sídlo) zo stránky finstat.sk
def extract_address(url):
    try:
        response = requests.get(url)
        if response.status_code == 200:
            soup = BeautifulSoup(response.content, 'html.parser')
            sidlo_element = soup.find('strong', string="Sídlo")
            if sidlo_element:
                li_element = sidlo_element.find_parent('li')
                if li_element:
                    address = li_element.find('span').get_text(separator=" ", strip=True)
                    return address
            return "Address not found"
        else:
            return "Failed to retrieve page"
    except Exception as e:
        return f"Error: {str(e)}"

# Iterujeme cez viacero stránok na zhromaždenie URL adries id, kontrolu dátumu a extrakciu adries
for page_num in range(1, 6):
    print(f"Processing page {page_num}...")

    finstat_data = get_finstat_urls_with_dates(page_num)

    for id_value, url, creation_date in finstat_data:
        address = extract_address(url)
        # Uložíme dáta do databázy
        save_to_database(id_value, url, address, creation_date.strftime('%Y-%m-%d'))

print("Dáta boli úspešne uložené do databázy")

# Close databazy
cursor.close()
db_connection.close()
