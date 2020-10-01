# TokensGame

Aplikacja stworzona w celach rekrutacyjnych do firmy PROGET.

## Treść zadania
Celem zadania jest napisanie modelu bardzo prostej gry planszowej. Gra polega na odwracaniu żetonów. Na jedną grę składa się 25 żetonów, 5 w pionie i 5 w poziomie. Każdy żeton ma 2 strony, zakrytą i odkrytą. Jeden żeton spośród całej puli to "żeton wygrywający". Gracz ma 60 sekund i tylko 5 prób na odkrycie zwycięskiego żetonu. Żeton wygrywający w każdej grze znajduje się na inne pozycji. Gracz wygrywa tylko wtedy kiedy w przeciągu 60 sekund odkryje wygrywający żeton w co najwyżej 5 próbach. Po odkryciu żetonu, który nie jest "żetonem wygrywającym", pozostaje on odkryty do końca gry. Gracz nie może odkryć 2 razy tego samego żetonu. Po odkryciu żetonu wygrywającego gra dobiega końca.

## Uruchomienie

1\. Pobranie wymaganych paczek i ich instalacja:
```composer log
composer install
```

2\. Uruchomienie aplikacji (w katalogu aplikacji):
```composer log
php bin/console app:tokens-game
```

## Opis rozwiązania

Aplikacja składa się z:
1. Właściwego kodu aplikacji znajdującego się w katalogu **[TokensGame](src/TokensGame)**,
2. Kodu, który obsługuje konsolę - **[TokensGameCommand](src/Command/TokensGameCommand.php)**,
3. Testów znajdujących się w katalogu **[spec](spec)**.