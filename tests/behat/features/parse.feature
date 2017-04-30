Feature: Parse
  Url parser tokenizes current browser URL

  Scenario: Default strategy
    Given I am on homepage
    Then the "#url" element should contain current URL
    Then the radiobutton "#regex" should be checked
    Then the radiobutton "#native" should not be checked
    Then the "#strategy" element should contain "REGEX"

  Scenario: NATIVE strategy (homepage)
    Given I am on homepage
    And the "#url" element should contain current URL
    When I select "native" from "radioStrategy"
    And I press "bsubmit"
    Then the "#strategy" element should contain "NATIVE"

  Scenario: REGEX strategy (homepage)
    Given I am on homepage
    And the "#url" element should contain current URL
    When I select "regex" from "radioStrategy"
    And I press "bsubmit"
    Then the "#strategy" element should contain "REGEX"

