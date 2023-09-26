import Activity from './Activity.vue'

describe('<Activity />', () => {
  it('renders', () => {
    // see: https://on.cypress.io/mounting-vue
    cy.mount(Activity)
  })
})