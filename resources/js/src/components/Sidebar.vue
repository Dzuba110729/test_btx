<template>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-img">
                <img src="/public/images/logo.svg" alt="logo">
            </div>
            <div class="logo">
                Enterprise Resource Planning
            </div>
        </div>
        <nav class="menu">
            <router-link to="/" class="menu-item active">Продукты</router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
};
</script>

<style scoped>
.sidebar {
    width: 180px;
    background-color: #374050;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.sidebar-header {
    width: 100%;
    height: 60px;
    display: flex;
}

.logo-img {
    width: 80px;
    background-color: #ffffff;
    border-radius: 0 0 20px 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo {
    width: 100px;
    font-size: 11px;
    line-height: 11px;
    color: #ffffff;
    padding: 7px 15px;
}

.menu {
    width: 100%;
}

.menu-item {
    display: block;
    width: 100%;
    height: 30px;
    padding-top: 13px;
    padding-left: 33px;
    text-decoration: none;
    color: #FFFFFFB2;
    font-size: 12px;
    line-height: 11px;
}
</style>
