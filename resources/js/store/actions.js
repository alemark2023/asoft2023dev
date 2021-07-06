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
        // Previenete limite de almacen exedido
        /*5MB per app per browser. According to the HTML5 spec, this limit can be increased by the user when needed; however, only a few browsers support this*/
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

}
