# Aplikacja Pogodowa WeatherFetch

## Opis Zadania
Zadaniem było stworzenie aplikacji webowej opartej na frameworku Symfony.
1. Aplikacja pobiera dane z API: [API Publiczne IMGW](https://danepubliczne.imgw.pl/apiinfo).
2. Pod adresem localhost/pogoda/{miasto} aplikacja wyświetla dane dla określonego miasta: nazwę miasta, datę i godzinę ostatniego pomiaru, zmierzoną temperaturę. Stylizacja strony nie jest kluczowa; jednak dane powinny być czytelnie prezentowane, zgodnie z semantyką HTML.
3. Pod adresem localhost/api/pogoda/{miasto}, aplikacja zwraca te same dane w formacie JSON.
4. Pod adresem localhost/api/pogoda/{miasto}/full, aplikacja zwraca pełen zakres danych dla danego miasta, pobranych z API źródłowego.
5. Zapewniona jest poprawna obsługa błędów – na przykład obsługa braku danych dla danego miasta lub braku komunikacji z serwerem API.

## Dodatkowe Punkty:
6. Logowanie błędów do pliku jest zaimplementowane.
7. Dostęp do API jest zabezpieczony za pomocą tokenu JWT (token generowany dla użytkownika z bazy danych).

Wszystkie wymagane punkty zostały zrealizowane.

## Repozytorium
- Repozytorium na GitHub: [WeatherFetch](https://github.com/EdiGch/weatherFetch)

## Aplikacja
- URL Aplikacji: [Aplikacja Pogodowa WeatherFetch](http://165.227.234.211:8000)

## Dokumentacja API
- URL Dokumentacji API: [Dokumentacja API](http://165.227.234.211:8000/api/doc)

## Dostawca Hostingu
- Hostowane na: [DigitalOcean](https://www.digitalocean.com)

