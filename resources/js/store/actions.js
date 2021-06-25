export default {
  loadConfiguration(store) {
    let w = localStorage.getItem('configuration')
    if (w !== null) {
      store.state.configuration = JSON.parse(w)
    }
     w = localStorage.getItem('customers')
    if (w !== null) {
      store.state.customers = JSON.parse(w)
    }
     w = localStorage.getItem('typeUser')
    if (w !== null) {
      store.state.typeUser = w
    }
  },
}
