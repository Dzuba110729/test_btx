<template>
    <div id="app" class="app-container">
        <Sidebar />
        <div class="main-content">
            <Header
                :currentUser="currentUser"
                @authClick="handleAuthClick"
                @userPopupClick="toggleUserPopup"
            />
            <div class="content">
                <ProductList
                    v-if="currentUser"
                    :products="products"
                    :isAdmin="currentUser?.isAdmin"
                    @productUpdated="fetchProducts" />
                <div class="add-product" v-if="currentUser?.isAdmin">
                    <button class="add-btn" @click="openPopup">Добавить</button>
                    <PopUpForm ref="popupForm" @productAdded="handleProductAdded" />
                </div>
            </div>
        </div>
        <AuthPopup
            v-if="showAuthPopup"
            :visible="true"
            @close="showAuthPopup = false"
            @login="handleLogin"
        />
        <UserPopup
            v-if="showUserPopup"
            :user="currentUser"
            @close="toggleUserPopup"
            @logout="handleLogout"
        />
    </div>
</template>

<script>
import Sidebar from './components/Sidebar.vue';
import Header from './components/Header.vue';
import PopUpForm from './components/PopupForms/PopUpForm.vue';
import ProductList from './components/ProductList.vue';
import AuthPopup from "./components/PopupForms/AuthPopup.vue";
import UserPopup from "./components/PopupForms/UserPopup.vue";
import axios from 'axios';

export default {
    components: {
        Sidebar,
        Header,
        PopUpForm,
        ProductList,
        AuthPopup,
        UserPopup
    },
    data() {
        return {
            currentUser: null,
            showAuthPopup: false,
            showUserPopup: false,
            products: []
        };
    },
    methods: {
        handleAuthClick() {
            this.showAuthPopup = true;
        },
        handleLogin(user) {
            this.currentUser = user;
            this.showAuthPopup = false;
            console.log("User logged in", user);
            localStorage.setItem('currentUser', JSON.stringify(user));
            this.fetchProducts();
        },
        openPopup() {
            this.$refs.popupForm.openPopup();
        },
        fetchProducts() {
            axios.get('/api/products')
                .then(response => {
                    this.products = response.data.data;
                })
                .catch(error => {
                    console.error('Ошибка при обновлении списка продуктов:', error);
                });
        },
        handleProductAdded(newProduct) {
            this.products.push(newProduct);
        },
        toggleUserPopup() {
            this.showUserPopup = !this.showUserPopup;
        },
        handleLogout() {
            this.currentUser = null;
            this.products = [];
            localStorage.removeItem('currentUser');
            this.toggleUserPopup();
        },
        restoreUserSession() {
            const storedUser = localStorage.getItem('currentUser');
            if (storedUser) {
                this.currentUser = JSON.parse(storedUser);
                this.fetchProducts();
            }
        }
    },
    mounted() {
        this.restoreUserSession();
        this.fetchProducts();
    }
};
</script>

<style scoped>
.app-container {
    display: flex;
    min-height: 100vh;
}

.main-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.content {
    display: flex;
    justify-content: space-between;
    padding-right: 18px;
    background-color: #F2F6FA;
    height: 100%;
    margin-top: 22px;
}

.add-product {
    margin-top: 17px;
}

.add-btn {
    padding: 9px 43px;
    background-color: #0FC5FF;
    color: white;
    font-size: 11px;
    line-height: 11px;
    font-weight: 500;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.add-btn:hover {
    opacity: .8;
}
</style>
