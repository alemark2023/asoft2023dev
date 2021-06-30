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
        writeLocal('config', JSON.stringify(config))
    },
    setCustomers(state, customers) {
        state.customers = customers
        writeLocal('customers', JSON.stringify(customers))
    },
    setOffices(state, offices) {
        state.offices = offices
        writeLocal('offices', JSON.stringify(offices))
    },
    setDocumentTypes(state, documentTypes) {
        state.documentTypes = documentTypes
        writeLocal('documentTypes', JSON.stringify(documentTypes))
    },
    setFiles(state, files) {
        state.files = files
        writeLocal('files', JSON.stringify(files))
    },
    setProcesses(state, processes) {
        state.processes = processes
        writeLocal('processes', JSON.stringify(processes))
    },
    setActions(state, actions) {
        state.actions = actions
        writeLocal('actions', JSON.stringify(actions))
    },

    setFile(state, file) {
        state.file = file
        writeLocal('file', JSON.stringify(file))
    },


    setWorkers(state, workers) {
        state.workers = workers
        writeLocal('workers', JSON.stringify(workers))
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
        writeLocal('warehouses', JSON.stringify(warehouses))
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
        writeLocal('all_items', JSON.stringify(all_items))
    },
}
