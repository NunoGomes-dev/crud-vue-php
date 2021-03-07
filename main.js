var app = new Vue({
    el: '#app',
    data: function () {
        return {
            users: [],
            errorMsg: "",
            successMsg: false,
            showAddModal: false,
            showEditModal: false,
            showDeleteModal: false,
            newUser: {
                name: "",
                email: "",
                password: ""
            },
            currentUser: {}
        }
    },
    mounted() {
        this.getAllUsers();
    },
    methods: {
        getAllUsers() {
            axios.get("http://localhost/projects/crud-vue-php/process.php?action=read").then(function (response) {
                if (response.data.error) {
                    app.errorMsg = response.data.message;
                } else {
                    app.users = response.data.users;
                }
            })
        },
        addUser() {
            this.clearMsgs();
            const formData = app.toFormData(app.newUser);
            axios.post("http://localhost/projects/crud-vue-php/process.php?action=create", formData).then(function (response) {
                app.newUser = {
                    name: "",
                    email: "",
                    password: ""
                };
                if (response.data.error) {
                    app.errorMsg = response.data.message;
                } else {
                    app.successMsg = response.data.message;
                    app.getAllUsers();
                }
            })
        },
        toFormData(obj) {
            var fd = new FormData();
            for (var i in obj) {
                fd.append(i, obj[i]);
            }
            return fd;
        },
        updateUser() {
            this.clearMsgs();
            const formData = app.toFormData(app.currentUser);
            axios.post("http://localhost/projects/crud-vue-php/process.php?action=update", formData).then(function (response) {
                app.currentUser = {};
                if (response.data.error) {
                    app.errorMsg = response.data.message;
                } else {
                    app.successMsg = response.data.message;
                    app.getAllUsers();
                }
            })
        },
        deleteUser() {
            this.clearMsgs();
            const formData = app.toFormData(app.currentUser);
            axios.post("http://localhost/projects/crud-vue-php/process.php?action=delete", formData).then(function (response) {
                app.currentUser = {};
                if (response.data.error) {
                    app.errorMsg = response.data.message;
                } else {
                    app.successMsg = response.data.message;
                    app.getAllUsers();
                }
            })
        },
        selectUser(user) {
            app.currentUser = user;
        },
        clearMsgs() {
            app.errorMsg = "";
            app.successMsg = "";
        }
    }
});