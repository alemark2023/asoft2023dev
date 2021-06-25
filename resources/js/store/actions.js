export default {
  loadConfiguration(store) {
    let w = localStorage.getItem('config')
    if (w !== null) {
      store.state.config = JSON.parse(w)
    }
     w = localStorage.getItem('customers')
    if (w !== null) {
      store.state.customers = JSON.parse(w)
    }
     w = localStorage.getItem('userType')
    if (w !== null) {
      store.state.userType = w
    }
  },
    loadWarehouses(store){
        let w = localStorage.getItem('warehouses')
        if (w !== null) {
            store.state.warehouses = JSON.parse(w)
        }
    },
    loadAllItems(store){
        let w = localStorage.getItem('all_items')
        if (w !== null) {
            let temp_item = JSON.parse(w);
            temp_item = temp_item.filter((item, index, self) =>
                index === self.findIndex((t) => (
                    t.id === item.id
                ))
            )
            store.state.all_items = temp_item
        }
    },

}
