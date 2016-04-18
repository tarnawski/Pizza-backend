Feature: Show information about current login user
  In order to have possibility to view information about me
  As a login user
  I need to be able to get details account

  Background:
    Given There are the following clients:
      | ID | Random ID                                             | URIs                                      | Secret                                              | Grant Types                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://pizza.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | Username    | Password          | Email            | Superadmin      | Enabled | Role     |
      | admin       | admin             | admin@admin.com  | true            | true    | ROLE_API |
    And There are the following access tokens:
      | ID | Client | User | Token                                                                                  | Expires at |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +2 days    |

  @cleanDB
  Scenario: Get information about my profile
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a GET request to "/api/user/me"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "id": @integer@,
      "username": "admin",
      "email": "admin@admin.com"
    }
    """
