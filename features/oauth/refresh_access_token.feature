#language en
Feature: Refresh Access Token
  In order to resfresh Access Token
  As a client
  I need to send request

  Background: There are all required data
    Given There are the following clients:
      | ID | Random ID                                             | URIs                                      | Secret                                              | Grant Types                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://pgs-soft.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | Username    | Password          | Email                 | Superadmin      | Enabled | Role     |
      | admin       | admin             | admin@pgs-soft.com    | true            | true    | ROLE_API |
    And There are the following access tokens:
      | ID | Client | User | Token                                                                                  | Expires at |
      | 1  | 1      | 1    | OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | -2 days    |
    And There are the following refresh tokens:
      | ID | Client | User | Token                                                                                  | Expires at |
      | 1  | 1      | 1    | 1WJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw | +10 days   |

  @cleanDB
  Scenario: Refreshing access token
    Given I set header "content-type" with value "application/json"
    When I send a POST request to "/oauth/v2/token" with body:
    """
    {
      "client_id": "1_6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg",
      "client_secret": "2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g",
      "grant_type": "refresh_token",
      "refresh_token": "1WJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    }
    """
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "access_token": @string@,
      "expires_in": 3600,
      "token_type": "bearer",
      "scope": @null@,
      "refresh_token": @string@
    }
    """
