Feature: Show product
  In order to have possibility to show list or single product
  As a login user
  I need to be able to get information about my product

  Background:
    Given There are the following clients:
      | ID | Random ID                                             | URIs                                      | Secret                                              | Grant Types                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://pizza.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | Username    | Password          | Email            | Superadmin      | Enabled | Role     |
      | admin       | admin             | admin@admin.com  | true            | true    | ROLE_API |
      | test        | test              | test@test.com    | false           | true    | ROLE_API |
    And There are the following access tokens:
      | ID | Client | User | Token                                                                                  | Expires at |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +2 days    |
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

  @cleanDB
  Scenario: Get list of products
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "api/types/1/products"
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

  @cleanDB
  Scenario: Get single product
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "api/types/1/products/1"
    Then the response code should be 200
    And the JSON response should match:
    """
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
    """

  @cleanDB
  Scenario: Get single product with invalid id
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "api/types/1/products/3"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "status": "Error",
      "message": "Product not found"
    }
    """