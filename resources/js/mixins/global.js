import moment from 'moment';
const mixins = {
    filters: {
        toDecimals(number, decimal = 2) {
            return Number(number).toFixed(decimal);
        },
        toDate(date) {
            if (date) {
                return moment(date).format('DD/MM/YYYY');
            }
            return '';
        },
        toTime(time) {
            if (time) {
                if (time.length === 5) {
                    return moment(time + ':00', 'HH:mm:ss').format('HH:mm:ss');
                }
                return moment(time, 'HH:mm:ss').format('HH:mm:ss');
            }
            return '';
        }
    },
    methods: {
        axiosError(error) {
            const response = error.response;
            const status = response.status;
            if (status === 422) {
                this.errors = response.data
            }
            if (status === 500) {
                this.$message({
                    type: 'info',
                    message: response.data.message
                  });
            }
        }
    }
}

export default mixins;
