import state from './state'
function writeLocal(variable, data) {
    if (data === undefined || data === 'undefined') {
        localStorage.removeItem(variable)
    } else {
        localStorage.setItem(variable, data)
    }
}
export default {
    setConfiguration(state, config) {
        if(config !== undefined && config.company !== undefined){
            state.company = config.company
            writeLocal('company', JSON.stringify(config.company))
        }
        if(config !== undefined && config.establishment !== undefined){
            state.establishment = config.establishment
            writeLocal('establishment', JSON.stringify(config.establishment))
        }
        state.config = config
        if(config === undefined){
            state.config=    JSON.parse(localStorage.getItem('config'))
            config = state.config;
        }
        writeLocal('config', JSON.stringify(config))
    },
    setEstablishment(state,establishment){
        writeLocal('establishment', JSON.stringify(establishment))
        return establishment;
    },
    setCompany(state,company){
        writeLocal('company', JSON.stringify(company))
        return company;
    },
    setCustomers(state, customers) {
        state.customers = customers
    },
    setCurrencys(state, currencys) {
        state.currencys = currencys
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

    canShowColorDialog(state, showColorDialog) {
        state.showColorDialog = showColorDialog
    },
    setColor(state, color) {
        state.color = color
    },
    setLoadingSubmit(state, loading_submit) {
        if(loading_submit === undefined) loading_submit = false;
        state.loading_submit = loading_submit
    },
    setWorkers(state, workers) {
        state.workers = workers
    },
    setOffice(state, office) {
        state.office = office
    },
    setColors(state, colors) {
        if(colors == undefined ) colors = [];
        state.colors = colors
    },
    setCatItemStatus(state, CatItemStatus) {
        if(CatItemStatus == undefined ) CatItemStatus = [];
        state.CatItemStatus = CatItemStatus
    },
    setCatItemUnitsPerPackage(state, CatItemUnitsPerPackage) {
        if(CatItemUnitsPerPackage == undefined ) CatItemUnitsPerPackage = [];
        state.CatItemUnitsPerPackage = CatItemUnitsPerPackage
    },
    setCatItemMoldCavity(state, CatItemMoldCavity) {
        if(CatItemMoldCavity == undefined ) CatItemMoldCavity = [];
        state.CatItemMoldCavity = CatItemMoldCavity
    },
    setCatItemMoldProperty(state, CatItemMoldProperty) {
        if(CatItemMoldProperty == undefined ) CatItemMoldProperty = [];
        state.CatItemMoldProperty = CatItemMoldProperty
    },
    setCatItemUnitBusiness(state, CatItemUnitBusiness) {
        if(CatItemUnitBusiness == undefined ) CatItemUnitBusiness = [];
        state.CatItemUnitBusiness = CatItemUnitBusiness
    },
    setCatItemPackageMeasurement(state, CatItemPackageMeasurement) {
        if(CatItemPackageMeasurement == undefined ) CatItemPackageMeasurement = [];
        state.CatItemPackageMeasurement = CatItemPackageMeasurement
    },

    setCatItemProductFamily(state, CatItemProductFamily) {
        if(CatItemProductFamily == undefined ) CatItemProductFamily = [];
        state.CatItemProductFamily = CatItemProductFamily
    },



    setRecords(state, records) {
        if(records == undefined ) records = [];
        state.records = records
    },
    setPagination(state, pagination) {
        if(pagination == undefined ) pagination = {
            current_page : 1,
            total : 0,
            per_page : 25,
        };
        state.pagination = pagination
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
