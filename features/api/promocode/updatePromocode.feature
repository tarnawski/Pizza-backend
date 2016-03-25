Feature: Update promo code
  In order to have possibility change promo codes belongs to application
  As a login user
  I need to be able to update promo code

  Background:
    Given There are the following clients:
      | ID | Random ID                                             | URIs                                      | Secret                                              | Grant Types                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://pgs-soft.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
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
    Given There are the following promo codes:
      | ID | Name         | Percent | Overall  | Value  | Code            | Available | ApplicationID |
      | 1  | PromoCode_1  | true    | false    | 5      | OWJkOGQzODliYTZ | true      | 1             |

  @cleanDB
  Scenario: Update promo code
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a PUT request to "/api/promocodes/1" with body:
    """
    {
      "name": "PromoCode_5",
      "percent": true,
      "overall": false,
      "value": 5,
      "code": "OWJkOGQzODliYTZ",
      "available": true
    }
    """
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "name": "PromoCode_5",
      "percent": true,
      "overall": false,
      "value": 5,
      "code": "OWJkOGQzODliYTZ",
      "available": true
    }
    """
