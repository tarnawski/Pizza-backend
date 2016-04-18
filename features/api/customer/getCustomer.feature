Feature: Show customer
  In order to have possibility to show list or single customer
  As a login user
  I need to be able to get information about my customers

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
    Given There are the following customers:
      | ID | FirstName  | LastName    | Email               | Phone     | Address                         | ApplicationID |
      | 1  | Janina     | Malinowska  | ugorski@gazeta.pl   | 887538836 | Gajowa 38, 50-519 Legnica       | 1             |
      | 2  | Anna       | Jakubowska  | kacper33@yahoo.com  | 865729206 | Jasna 40/73, 07-916 Sandomierz  | 1             |

  @cleanDB
  Scenario: Get list of customers
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/customers"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "page": @integer@,
      "limit": @integer@,
      "pages": @integer@,
      "total": @integer@,
      "_links": {
        "self": {
          "href": "@string@"
        },
        "first": {
          "href": "@string@"
        },
        "last": {
          "href": "@string@"
        }
      },
      "_embedded": {
        "items": [
          {
            "id": @integer@,
            "first_name": "Anna",
            "last_name": "Jakubowska",
            "email": "kacper33@yahoo.com",
            "phone": "865729206",
            "address": "Jasna 40/73, 07-916 Sandomierz"
          },
          {
            "id": @integer@,
            "first_name": "Janina",
            "last_name": "Malinowska",
            "email": "ugorski@gazeta.pl",
            "phone": "887538836",
            "address": "Gajowa 38, 50-519 Legnica"
          }
        ]
        }
      }
    """

  @cleanDB
  Scenario: Get single customer
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/customers/1"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "first_name": "Janina",
      "last_name": "Malinowska",
      "email": "ugorski@gazeta.pl",
      "phone": "887538836",
      "address": "Gajowa 38, 50-519 Legnica"
    }
    """

  @cleanDB
  Scenario: Get single customer with invalid id
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/customers/3"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "status": "Error",
      "message": "Customer not found"
    }
    """