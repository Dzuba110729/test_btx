<template>
    <header class="header">
        <div class="header-tabs">
            <router-link to="/" class="header-tab active">Продукты</router-link>
        </div>
        <div class="header-user">
            <span class="header-user-info" @click="currentUser ? $emit('userPopupClick') : $emit('authClick')">
                {{ currentUser ? `${currentUser.surname} ${currentUser.name} ${currentUser.patronymic}` : "Войти" }}
            </span>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        currentUser: {
            type: Object,
            required: false,
        },
    },
    name: "Header",
};
</script>

<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    padding: 25px 30px 0 20px;
}

.header-tabs {
    font-size: 11px;
    line-height: 11px;
    text-transform: uppercase;
}
.header-tab {
    padding-bottom: 18px;
    border-bottom: 3px solid inherit;
}

.header-tab.active {
    color: #ED1C24;
    border-bottom: 3px solid #ED1C24;
}

.header-user {
    font-size: 11px;
    line-height: 11px;
    color: #A6AFB8;
}

.header-user-info {
    padding-bottom: 21px;
    cursor: pointer;
}
</style>
