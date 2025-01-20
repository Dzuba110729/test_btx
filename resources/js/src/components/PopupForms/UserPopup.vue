<template>
    <div class="user-popup">
        <div class="user-popup-content">
            <div class="user-popup-content-block">
                <div class="user-popup-title">Информация о пользователе</div>
                <div class="user-popup-text">
                    <p><strong>ФИО:</strong> {{ user.surname }} {{ user.name }} {{ user.patronymic }}</p>
                    <p><strong>Email:</strong> {{ user.email }}</p>
                    <p><strong>Роль:</strong> {{ user.isAdmin ? 'Администратор' : 'Пользователь' }}</p>
                </div>
                <div class="form-actions">
                    <button class="form-actions-btn" @click="$emit('logout')">Выйти</button>
                </div>
            </div>
            <button class="close-btn" @click="$emit('close')"></button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    emits: ["close", "logout"]
};
</script>

<style scoped>
.user-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.user-popup-content {
    background: #374050;
    padding: 27px 12px;
    border-radius: 5px;
    width: 630px;
    min-height: 390px;
    color: #fff;
    display: flex;
    justify-content: space-between;
}

.user-popup-content-block {
    display: flex;
    flex-direction: column;
}

.user-popup-title {
    font-size: 20px;
    font-weight: 700;
    line-height: 11px;
    color: #fff;
    margin-right: 50px;
}

.user-popup-text {
    margin-top: 30px;
}

.user-popup-text p {
    margin-bottom: 15px;
}

.form-actions {
    width: 140px;
    height: 30px;
    border-radius: 5px;
    text-align: center;
    background-color: #5FC6F1;
    margin-top: auto;
}

.form-actions-btn {
    width: 100%;
    height: 100%;
    font-size: 11px;
    line-height: 11px;
    color: #fff;
}

.close-btn {
    width: 30px;
    height: 30px;
    cursor: pointer;
    background-image: url("/public/images/Close_round.svg");
    background-position: center;
    background-size: cover;
}
</style>
