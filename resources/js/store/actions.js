import state from './state'
import {getUniqueArray} from "../helpers/functions";

function readStorageData(variable, json = false) {
    let w = localStorage.getItem(variable)
    if (w === 'undefined') {
        w = null;
    }
    if (json === true) {
        w = JSON.parse(w)
    }
    return w;
}

export default {
    loadConfiguration(store) {
        state.config = readStorageData('config', true)
        state.customers = readStorageData('customers', true)
        state.userType = readStorageData('userType', false)
        state.company = readStorageData('company', true)
        state.establishment =  readStorageData('establishment', true)
        if(state.colors === undefined) state.colors = []
        if(state.CatItemMoldProperty === undefined) state.CatItemMoldProperty = []
        if(state.CatItemUnitBusiness === undefined) state.CatItemUnitBusiness = []
        if(state.CatItemStatus === undefined) state.CatItemStatus = []
        if(state.CatItemProductFamily === undefined) state.CatItemProductFamily = []
        if(state.CatItemPackageMeasurement === undefined) state.CatItemPackageMeasurement = []
        if(state.CatItemUnitsPerPackage === undefined) state.CatItemUnitsPerPackage = []
        if(state.CatItemMoldCavity === undefined) state.CatItemMoldCavity = []


        if(state.loading_submit === undefined) state.loading_submit = false
        // Previenete limite de almacen exedido
        /*
        5MB per app per browser. According to the HTML5 spec, this limit can be increased by the user when needed;
         however, only a few browsers support this
         */
        // alternativa posible sessionStorage
        localStorage.removeItem('customers')
        localStorage.removeItem('offices')
        localStorage.removeItem('files')
        localStorage.removeItem('processes')
        localStorage.removeItem('actions')
        localStorage.removeItem('workers')
        localStorage.removeItem('warehouses')
        localStorage.removeItem('all_items')
    },
    loadWarehouses(store) {
        if(state.warehouses === undefined) state.warehouses = [];
        // state.warehouses = readStorageData('warehouses', true)
    },
    loadOffices(store) {
        if(state.offices === undefined) state.offices = [];
        // state.offices = readStorageData('offices', true)
    },
    loadCustomers(store) {
        if(state.customers === undefined) state.customers = [];
        // state.customers = readStorageData('customers', true)
    },
    loadCurrencys(store) {
        if(state.currencys === undefined) state.currencys = [];
        // state.customers = readStorageData('customers', true)
    },
    loadActions(store) {
        if(state.actions === undefined) state.actions = [];
        // state.actions = readStorageData('actions', true)
    },
    loadProcesses(store) {
        if(state.processes === undefined) state.processes = [];
        // state.processes = readStorageData('processes', true)
    },
    loadFiles(store) {
        if(state.files === undefined) state.files = [];
        // state.files = readStorageData('files', true)
    },
    loadDocumentTypes(store) {
        state.documentTypes = readStorageData('documentTypes', true)
    },
    loadWorkers(store) {
        if(state.workers === undefined) state.workers = [];
        // state.workers = readStorageData('workers', true)
    },
    loadAllItems(store) {
        if(state.all_items === undefined) state.all_items = [];
        // state.all_items = getUniqueArray(readStorageData('all_items', true), ['id'])
    },
    loadCompany(store){
        let t  = readStorageData('company', true)
        if(t !==null){
            state.company = t
        }else{
            state.company = {
                logo:null,
                    name:'',
            };
        }
    },
    loadEstablishment(store){
        let t  = readStorageData('establishment', true)
        if(t !==null){
            state.establishment = t
        }else{
            state.establishment = {
                address:'-',
                district: {description:''},
                province: {description:''},
                department: {description:''},
                country: {description:''},
                telephone:'-',
                email:null,
            };
        }
    },
}
