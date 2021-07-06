function writeLocal(variable, data) {
    if (data === undefined || data === 'undefined') {
        localStorage.removeItem(variable)
    } else {
        localStorage.setItem(variable, data)
    }
}
export default {
    setConfiguration(state, config) {
        state.config = config
        if(config === undefined){
            state.config=    JSON.parse(localStorage.getItem('config'))
            config = state.config;
        }
        writeLocal('config', JSON.stringify(config))
    },
    setCustomers(state, customers) {
        state.customers = customers
    },
    setOffices(state, offices) {
        state.offices = offices
    },
    setDocumentTypes(state, documentTypes) {
        state.documentTypes = documentTypes
        writeLocal('documentTypes', JSON.stringify(documentTypes))
    },
    setFiles(state, files) {
        state.files = files
    },
    setProcesses(state, processes) {
        state.processes = processes
    },
    setActions(state, actions) {
        state.actions = actions
    },

    setFile(state, file) {
        state.file = file
    },


    setWorkers(state, workers) {
        state.workers = workers
    },
    setOffice(state, office) {
        state.office = office
    },
    setTypeUser(state, userType) {
        state.userType = userType
        writeLocal('userType', userType)
    },
    setWarehouses(state, warehouses) {
        state.warehouses = warehouses
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
    },
}
