describe('My First End to End Test', () => {
  it('Visits the PHP Ajax Crud... and performs full end to end cycle', () => {
    cy.visit('http://localhost:8080/');
    cy.contains('First Name');
    cy.contains('Last Name');
    cy.contains('Edit');
    cy.contains('Delete');
    cy.get('h1')
      .should('contain','PHP Ajax Crud using JQuery UI Dialog');
  });

  it('Adds a record', () => {
    cy.get('#add')
      .contains('Add')
      .click();
    cy.get('#first_name')
      .type("Jane");
    cy.get('#last_name')
      .type("Jones");
    cy.get('#form_action')
      .click();
    cy.get('#action_alert > p')
      .should('contain','Data inserted...');
    cy.get('[aria-describedby="action_alert"]')
      .click();
    cy.get('tr')
      .last()
      .should('contain','Jane')
      .contains('Jane')
    cy.get('tr')
      .last()
      .contains('Jones');
  });

  it('Fetches a record', () => {
    cy.get("button[name*='edit']")
      .last()
      .click();
    cy.contains('Edit Data');
    cy.get('#first_name')
      .should('have.value','Jane');
    cy.get('#last_name')
      .should('have.value','Jones');
  });

  it('Edits a record', () => {
    cy.get('#first_name')
      .should('have.value','Jane');
    cy.get('#first_name')
      .clear()
      .type('Joe');
    cy.get('#last_name')
      .clear()
      .type('Evans{enter}');
    cy.get('#action_alert > p').should('contain','Data updated...');
    cy.get('[aria-describedby="action_alert"]')
      .click();
    cy.get('tr')
      .last()
      .should('contain','Joe');
    cy.get('tr')
      .last()
      .should('contain','Evans')
      .contains('Evans');
  });

  it('Deletes a record', () => {
    cy.get('tbody')
      .should('contain','Evans')
      .contains('Evans');
    cy.get("button[name*='delete']")
      .last()
      .click();
    cy.contains('Confirmation');
    cy.get('#delete_confirmation > p')
      .should('contain','Are you sure you want to Delete this data?');
    cy.get('.ui-dialog-buttonset')
      .last()
      .contains('Cancel');
    cy.get('.ui-dialog-buttonset')
      .first()
      .contains('Yes')
      .click();
    cy.wait(10)
    cy.get('#ui-id-2')
      .contains('Action');
    cy.get('#action_alert > p')
      .first()
      .should('contain','Data deleted')
      .type('{esc}');
  });
})
