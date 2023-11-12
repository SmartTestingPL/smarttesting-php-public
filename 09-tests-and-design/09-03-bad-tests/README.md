# Przykłady złych testów

* Brak asercji - Klasa `_01_NoAssertionsTest` i zawsze przechodzący test. Pokazujemy problem z ukrywaniem asercji w metodach pomocniczych - czasami metoda jest utworzona, a asercji brakuje. W przypadku PHPUnit ten problem można dość prosto rozwiązać pilnując aby flaga `beStrictAboutTestsThatDoNotTestAnything` była ustawionia na `true` (domyślna wartość).

* Za dużo mocków - Klasa `_02_DoesMockitoWorkTest` i operowanie tylko na mockach. De facto nie testujemy nic, poza tym, że framework do mockowania działa.

* Mockowanie wywołań statycznych - Klasa `_03_MockAllTheThingsTest` zawiera klasę `_03_FraudService`, gdzie wykorzystujemy "utility class" ze statycznymi metodami: nasz własny `DatabaseAccessor`. W teście `test_should_find_any_empty_name` mockujemy wszystko co się da i w pierwszej kolejności próbujemy ogarnąć mockowanie iteratora.

* Mockowanie wywołań statycznych c.d. -  Klasa `03_MockAllTheThingsTest` i tym razem bierzemy się za `DatabaseAccessor`. Wiemy już, że raczej nie powinniśmy mockować tego wywołania. Test `test_should_do_some_work_in_database_when_empty_string_found` pokazuje jak to zrobić odpowiednio.

* Stanowość - Klasa `_04_PotentialFraudServiceTest` pokazuje problemy związane ze stanowością w testach.

