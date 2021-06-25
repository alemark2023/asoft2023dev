export default {
    setConfiguration(state, config) {
        state.config = config
        localStorage.setItem('config', JSON.stringify(config))
    },
    setCustomers(state, customers) {
        state.customers = customers
        localStorage.setItem('customers', JSON.stringify(customers))
    },
    setTypeUser(state, userType) {
        state.userType = userType
        localStorage.setItem('userType', userType)
    },
    setWarehouses(state, warehouses) {
        state.warehouses = warehouses
        localStorage.setItem('warehouses', JSON.stringify(warehouses))
    },
    setAllItems(state, all_items) {
        if(state.all_items !== undefined) {
            let temp_item = [state.all_items, ...all_items]
            temp_item = temp_item.filter((item, index, self) =>
                index === self.findIndex((t) => (
                    t.id === item.id
                ))
            )
            state.all_items = temp_item
        }else{
            state.all_items = all_items
        }
        localStorage.setItem('all_items', JSON.stringify(all_items))
    },
}
