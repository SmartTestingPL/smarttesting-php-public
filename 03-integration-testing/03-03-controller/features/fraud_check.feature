Feature: Check fraud
  Scenario: Reject loan application when person is too young
    Given  I add "Content-Type" header equal to "application/json"
    When I send a "POST" request to "/fraudCheck" with body:
    """
    {
      "uuid": "7b3e02b3-6b1a-4e75-bdad-cef5b279b074",
      "name": "Zbigniew",
      "surname": "Zamłodowski",
      "dateOfBirth": "2020-01-01",
      "gender": "male",
      "nationalIdentificationNumber": "18210116954"
    }
    """
    Then the response status code should be 401

  Scenario: Accept loan application when person is ok
    Given  I add "Content-Type" header equal to "application/json"
    When I send a "POST" request to "/fraudCheck" with body:
    """
    {
      "uuid": "7b3e02b3-6b1a-4e75-bdad-cef5b279b074",
      "name": "Zbigniew",
      "surname": "Zamłodowski",
      "dateOfBirth": "1960-01-01",
      "gender": "male",
      "nationalIdentificationNumber": "18210116954"
    }
    """
    Then the response status code should be 200
