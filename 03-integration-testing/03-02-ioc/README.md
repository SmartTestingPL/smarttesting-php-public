# 03-02 Inversion of Control

## W jaki sposób tworzysz obiekty? [01]

### Kod

Tylko `_01_IocTests.php`

### Notatki

Niezależnie od tego, czy używasz obiektowego języka programowania, czy programujesz funkcyjnie, w jakiś sposób musisz 
uzyskać dany obiekt lub funkcję. Jedną z opcji jest ich ręczne zainstacjonowanie. Co w przypadku złożeń funkcji albo 
stworzenia bardziej skomplikowanych obiektów? Spójrzmy na następujący przykład kodu `01_IocTests.php`.

Widzimy, aby stworzyć finalny obiekt, musimy utworzyć 3 inne, które też potrzebują swoich zależności, 
które na dodatek są współdzielone między sobą. Ponadto mamy połączenie samego tworzenia obiektów, z logika biznesową, 
która pojawia nam się w ostatniej linijce.

Czy ten kod jest zły?

Absolutnie nie! Mamy pełną kontrolę nad tworzeniem obiektów i ich cyklem życia. Nie potrzebujemy żadnych dodatkowych 
frameworków lub bibliotek, żeby nasz system działał poprawnie, żeby zarabiał. Czy jest testowalny? Jak najbardziej, gdyż to my 
kontrolujemy jak obiekty są tworzone, zatem dla testów moglibyśmy tworzyć inne.

Co jeśli chcielibyśmy dodać kolejną weryfikację? Musimy zmienić kod!

Czy istnieją inne sposoby? Czy można odseparować tworzenie obiektów, ich konfigurację od ich użycia? 
Tak! Np. podejście Inversion of Control.

## Co to jest Inversion of Control? [02]

### Kod

Najpierw `config/services.yaml`, później `_02_SymfonyDiTest.php`.

### Notatki

#### services.yaml

W różnych językach programowania istnieją frameworki, które wprowadzają wspomniany mechanizm “Odwrócenia Kontroli”.  
Zobaczmy na przykładzie PHP i Symfony jak wyglądałoby użycie tego frameworka do rozdzielenia konstrukcji od użycia 
danego obiektu.

Symfony "kompiluje" kontener zależności, w ramach którego zarządza życiem obiektów. 
Obiekt zarejestrowany w kontenerze nazywa się serwisem. Symfony domyślnie używa do opisania konfiguracji pliku `yaml`.
Można to również zrobić przy pomocy plików `xml` oraz w czystym `php`: https://symfony.com/doc/current/service_container.html#creating-configuring-services-in-the-container

Niezależnie od użytego frameworka lub języka programowania, koncepcja pozostaje dokładnie ta sama. 
Mamy swoisty schemat stanu naszego systemu, składającego się z obiektów.

Symfony zarządza życiem obiektów. Zarządca tych obiektów potrafi wstrzyknąć jedne obiekty w drugie oraz grupować je w kolekcje.

Jeśli spojrzymy na poprzedni przykład, gdzie żeby dodać weryfikację musimy zmienić główny kod aplikacji, 
to w przypadku IOC, wystarczy, że zdefiniujemy konfigurację nowej weryfikacji i wówczas IOC samo wstrzyknie nam tę 
instancję do wymaganej kolekcji.

#### _02_SymfonyDiTest.php

Tak zdefiniowany schemat systemu jesteśmy w stanie przekazać do zarządcy, który będzie w stanie utworzyć te obiekty
 w odpowiedniej kolejności i utworzyć graf zależności między nimi.

Następnie z zarządcą komunikujemy się w celu wyciągnięcia już utworzonego obiektu.
Na nim możemy już operować w zakresie logiki biznesowej. W przypadku Symfony zarządcą jest kontener zależności ()

## IOC/DI i testowanie [03,04]

### Kod

Wpierw `config/services_test.yaml` i `_03_SymfonyTestDiTest.php`, potem `_04_CustomerVerificationTest.php`

### Notatki

Czy uruchamiać cały kernel za każdym razem?

Naturalnym pytaniem jest jak testować naszą aplikację, która używa Dependency Injection. 
W pierwszej chwili można odpowiedzieć, że należy uruchamiać cały kontekst, który poustawia nam drzewo zależności obiektów, 
a następnie wyciągnąć obiekt leżący w zakresie naszych zainteresowań (SUT - system under test) i przeprowadzić na nim testy.
Jest to poprawne podejście, gdyż rzeczywiście nasz kontekst będzie ustawiony w sposób najbardziej zbliżony do systemu 
produkcyjnego (należy pamiętać o testowych zależnościach). Inna sprawa, że niektóre frameworki bazują na analizie 
naszych źródeł i zależności, żeby to drzewo zbudować, co oczywiście może trwać. 
Pytanie czy nie ma innego podejścia, niż uruchamianie całej maszynerii po to, żeby kontekst postawić?

Innym podejściem może być użycie wspomnianego schematu konstrukcji obiektów, żeby nasz moduły zbudować ręcznie. Schemat zawiera definicje jak konstruować obiekty, dzięki czemu możemy schemat rozszerzyć o konstrukcje testowe. W ten sposób nie potrzebujemy stawiać całego kontekstu żeby przetestować komunikacji między obiektami.

Interakcje zewnętrzne (z bazą danych / kolejkami itd.) możemy zamienić na komponenty działające w pamięci. W naszym teście możemy potem wywołać testowy element schematu konstrukcyjnego, który reużywa produkcyjnych komponentów z testowymi detalami.

Następujący przykład powinien opisać tę sytuację.

#### services_test.yaml

Kontener zależności może zostać zbudowany (w zależności od środowiska) z różną konfiguracją. 
W pliku `services_test.yaml` widzimy, że nadpisany jest tylko jeden komponent. Reszta konfiguracji pozostaje bez zmian.

#### _03_SymfonyTestDiTest.php

Pokazujemy jak ręcznie możemy podmienić obiekt, wykorzystując produkcyjny schemat konfiguracyjny.

#### _04_CustomerVerificationTest.php

Pokazujemy jak może wyglądać integracja kontenera zależności z frameworkiem Symfony.
W odróżnieniu od testów w 02 i 03, nie tworzymy tutaj kontenera ręcznie, budowany jest natomiast całe jądro Symfony
(które będziemy wykorzystywać później w przypadku innych rodzajów testów).
