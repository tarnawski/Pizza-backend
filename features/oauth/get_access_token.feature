#language en
Feature: Create Access Token
  In order to generate Access Token
  As a client
  I need to send request

  Background: There are all required data
    Given There are the following clients:
      | ID | Random ID                                             | URIs                                      | Secret                                              | Grant Types                                                         |
      | 1  | 6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg    | http://example.com,http://pgs-soft.com    | 2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g  | authorization_code,client_credentials,refresh_token,password,token  |
    And there are the following users:
      | Username    | Password          | Email                 | Superadmin      | Enabled | Role     |
      | admin       | admin             | admin@pgs-soft.com    | true            | true    | ROLE_API |

  @cleanDB
  Scenario: Get Access Token By User and Password
    Given I set header "content-type" with value "application/json"
    When I send a POST request to "/oauth/v2/token" with body:
    """
    {
      "username": "admin",
      "password": "admin",
      "grant_type": "password",
      "client_id": "1_6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg",
      "client_secret": "2vtd632tcku88cgssgwkk0o8o0gcs0o4ook8g0wc8gskgc8k8g"
    }
    """
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "access_token": @string@,
      "expires_in": 3600,
      "token_type": @string@,
      "scope": @null@,
      "refresh_token": @string@
    }
    """

  @cleanDB
  Scenario: Get Access Token By User and Password for invalid credentials
    Given I set header "content-type" with value "application/json"
    When I send a POST request to "/oauth/v2/token" with body:
    """
    {
      "username": "admin-asd",
      "password": "admin-asdsa",
      "grant_type": "password",
      "client_id": "1_6035k9f52fc4gwskckowc8s0ws8swcco4ck0sk84owg4kg8kcg"
    }
    """
    Then the response code should be 400
    And the JSON response should match:
    """
      {
      "error":"invalid_client",
      "error_description":"The client credentials are invalid"
      }
    """
