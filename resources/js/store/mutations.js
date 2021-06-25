export default {
    setConfiguration(state, configuration) {
        state.configuration = configuration
        localStorage.setItem('configuration', JSON.stringify(configuration))
    },
    setCustomers(state, customers) {
        state.customers = customers
        localStorage.setItem('customers', JSON.stringify(customers))
    },
    setTypeUser(state, typeUser) {
        state.typeUser = typeUser
        localStorage.setItem('typeUser', typeUser)
    },
}
