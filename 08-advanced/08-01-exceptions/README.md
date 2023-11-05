# 08-01 Testowanie wyjątków

## Kod

Przykład weryfikacji po nazwisku `_01_NameVerification.php`, który loguje na konsoli informacje o kliencie.

W `_02_NameVerificationTest.php` znajdują się testy weryfikujące na różne sposoby rzucany wyjątek. Zaczynamy od najbardziej generycznego testu, który łapie `InvalidArgumentException` - `test_should_throw_an_exception_when_checking_verification`. Test przechodzi przez przypadek. `IAE` leci, gdyż w klasie `Person` następuje weryfikacja pola `gender` które w teście jest inicjowane złą wartością (uppercase).

Możemy weryfikować wiadomość przy rzuconym wyjątku tak jak w przypadku testu `test_should_throw_an_exception_when_checking_verification_only`.

Zakładając, że z jakiegoś powodu domenowego nasza klasa weryfikacyjna nie może obsłużyć błędnych sytuacji i musi rzucić wyjątek, to ten wyjątek powinien być wyjątkiem związanym z cyklem życia naszej aplikacji. Przypuśćmy, że tworzymy sobie wyjątek `_04_VerificationException`, który jako wyjątek domenowy (`_03_DomainException`) może zostać obsłużony gdzieś w innej części naszej aplikacji.

Nasza klasa weryfikująca mogłaby wówczas wyglądać tak jak `_05_NameWithCustomExceptionVerification`.

Test wtedy mógłby dokonywać asercji na podstawie rzuconego wyjątku tak jak w `test_should_fail_verification_when_name_is_invalid` lub `test_should_fail_verification_when_name_is_invalid_and_assertion_is_done_manually`, jeśli nie mamy dostępu do bibliotek do wykonywania takich asercji.
