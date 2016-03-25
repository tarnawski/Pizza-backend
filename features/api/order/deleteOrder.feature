Feature: Delete Order
  In order to have possibility change order belongs to application
  As a login user
  I need to be able to delete order

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
    Given There are the following customers:
      | ID | FirstName  | LastName    | Email               | Phone     | Address                         | ApplicationID |
      | 1  | Janina     | Malinowska  | ugorski@gazeta.pl   | 887538836 | Gajowa 38, 50-519 Legnica       | 1             |
      | 2  | Anna       | Jakubowska  | kacper33@yahoo.com  | 865729206 | Jasna 40/73, 07-916 Sandomierz  | 1             |
    Given There are the following types:
      | ID | Name    | ApplicationID |
      | 1  | Type_1  |  1            |
      | 2  | Type_2  |  1            |
    Given There are the following prices:
      | ID | Type     | Value |
      | 1  | Prices_1 | 12.50 |
      | 2  | Prices_2 | 10.50 |
      | 3  | Prices_3 | 17.50 |
    Given There are the following products:
      | ID | Name         | Description                |  Available   | TypeID |  PriceID | ApplicationID |
      | 1  | Product_1    | Short description number 1 | true         | 1      |  1, 2    | 1             |
      | 2  | Product_2    | Short description number 2 | true         | 2      |  3       | 1             |
    Given There are the following orders:
      | ID | CustomerID | ApplicationID | Realized | Description          |
      | 1  | 1          |  1            | false    |  Short description 1 |
      | 2  | 2          |  1            | false    |  Short description 2 |
    Given There are the following items:
      | ID | ProductID |  PriceID | OrderID | Count |
      | 1  |  1        |  1       | 1       | 1     |
      | 1  |  2        |  3       | 1       | 1     |
      | 1  |  1        |  2       | 2       | 1     |

  @cleanDB
  Scenario: Delete order
    Given I set header "Authorization" with value "Bearer OWJkOGQzODliYTZjNTk3YTM1MmY0OTY2NjRlYTk2YmRmM2ZhNGE5YmZmMWVlYTg4MTllMmMxMzg3NzA4NGU5Nw"
    When I send a DELETE request to "/api/orders/1"
    Then the response code should be 200
    And the JSON response should match:
    """
    {
      "status": "Removed",
      "message": "Order properly removed"
    }
    """
