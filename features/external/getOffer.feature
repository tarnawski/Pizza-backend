Feature: Show application offers
  In order to have possibility to view application offers
  As a login user
  I need to be able to get offer

  Background:
    Given There are the following applications:
      | ID | Name           | Description                 | Homepage            | Demo  | UserID |
      | 1  | Application_1  | Short description number 1  | http://www.demo1.pl | true  | 1      |
    Given There are the following types:
      | ID | Name    | ApplicationID |
      | 1  | Type_1  |  1            |
    Given There are the following prices:
      | ID | Type     | Value |
      | 1  | Prices_1 | 12.50 |
      | 2  | Prices_2 | 10.50 |
      | 3  | Prices_3 | 17.50 |
    Given There are the following products:
      | ID | Name         | Description                |  Available   | TypeID |  PriceID | ApplicationID |
      | 1  | Product_1    | Short description number 1 | true         | 1      |  1, 2    | 1             |
      | 2  | Product_2    | Short description number 2 | true         | 1      |  3       | 1             |

  @wip
  Scenario: Get offer
    When I send a GET request to "/ext/get/1"
    Then the response code should be 200
    And the JSON response should match:
    """
    [
      {
        "id": @integer@,
        "name": "Product_2",
        "description": "Short description number 2",
        "available": true,
        "prices": [
          {
            "id": @integer@,
            "type": "Prices_3",
            "value": 17.50
          }
        ]
      },
      {
        "id": @integer@,
        "name": "Product_1",
        "description": "Short description number 1",
        "available": true,
        "prices": [
          {
            "id": @integer@,
            "type": "Prices_1",
            "value": 12.50
          },
          {
            "id": @integer@,
            "type": "Prices_2",
            "value": 10.50
          }
        ]
      }
    ]
    """