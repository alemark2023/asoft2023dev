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
    },
    loadWarehouses(store) {
        state.warehouses = readStorageData('warehouses', true)
    },
    loadOffices(store) {
        state.offices = readStorageData('offices', true)
    },
    loadCustomers(store) {
        state.customers = readStorageData('customers', true)
    },
    loadActions(store) {
        state.actions = readStorageData('actions', true)
    },
    loadProcesses(store) {
        state.processes = readStorageData('processes', true)
    },
    loadFiles(store) {
        state.files = readStorageData('files', true)
    },
    loadDocumentTypes(store) {
        state.documentTypes = readStorageData('documentTypes', true)
    },
    loadWorkers(store) {
        state.workers = readStorageData('workers', true)
    },
    loadAllItems(store) {
        state.all_items = getUniqueArray(readStorageData('all_items', true), ['id'])
    },

}
