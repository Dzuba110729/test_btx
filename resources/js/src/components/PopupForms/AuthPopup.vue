<template>
    <div class="auth-popup" v-if="visible">
        <div class="auth-popup-contents">
            <div class="auth-popup-content">
                <div class="auth-popup-title">Авторизация</div>
                <form class="auth-popup-form" @submit.prevent="handleLogin">
                    <div>
                        <label for="name">Имя:</label>
                        <input type="text" id="name" v-model="user.name" required />
                    </div>
                    <div>
                        <label for="surname">Фамилия:</label>
                        <input type="text" id="surname" v-model="user.surname" required />
                    </div>
                    <div>
                        <label for="patronymic">Отчество:</label>
                        <input type="text" id="patronymic" v-model="user.patronymic" required />
                    </div>
                    <div>
                        <label for="email">Электронная почта:</label>
                        <input type="email" id="email" v-model="user.email" required />
                    </div>
                    <div>
                        <label for="isAdmin">Администратор:</label>
                        <input type="checkbox" id="isAdmin" v-model="user.isAdmin" />
                    </div>
                    <div class="form-actions">
                        <button class="form-actions-btn" type="submit">Войти</button>
                    </div>
                </form>
            </div>
            <button class="close-btn" @click="closePopup"></button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        visible: Boolean,
    },
    emits: ["close", "login"],
    data() {
        return {
            user: {
                name: "",
                surname: "",
                patronymic: "",
                email: "",
                isAdmin: false,
            },
        };
    },
    methods: {
        handleLogin() {
            if (this.user.name && this.user.surname && this.user.email) {
                this.$emit("login", this.user);
            } else {
                alert("Пожалуйста, заполните все обязательные поля!");
            }
        },
        closePopup() {
            this.$emit("close");
        },
    },
};
</script>

<style scoped>
.auth-popup {
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
.auth-popup-contents {
    background: #374050;
    padding: 27px 12px;
    border-radius: 5px;
    width: 630px;
    min-height: 390px;
    color: #fff;
    display: flex;
    justify-content: space-between;
}

.auth-popup-title {
    font-size: 20px;
    font-weight: 700;
    line-height: 11px;
    color: #fff;
    margin-right: 50px;
}

.auth-popup-form {
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 500px;
}

.auth-popup-form div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.auth-popup-form div label {
    display: block;
    margin-bottom: 8px;
    font-size: 9px;
    line-height: 11px;
    color: #fff;
}

.auth-popup-form div input {
    width: 472px;
    height: 30px;
    padding: 8px;
    border-radius: 5px;
    font-size: 11px;
    line-height: 11px;
    font-weight: 300;
    color: #000000;
}

.auth-popup-form div:nth-child(5) input {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

input:focus {
    outline: none;
}


.form-actions {
    margin-top: 21px;
    width: 140px;
    height: 30px;
    border-radius: 5px;
    text-align: center;
    background-color: #5FC6F1;
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
