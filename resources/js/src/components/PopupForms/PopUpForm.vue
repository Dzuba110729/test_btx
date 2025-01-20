<template>
    <div v-if="show" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <div class="popup-header-title">Добавить продукт</div>
                <button class="close-btn" @click="closePopup"></button>
            </div>
            <form @submit.prevent="submitProduct">
                <div class="form-group form-group-article">
                    <label for="article">Артикул</label>
                    <input type="text" id="article" v-model="newProduct.article" class="form-control" :class="{ 'is-invalid': errors.article }"  required />
                    <div v-if="errors.article" class="invalid-feedback">
                        {{ errors.article[0] }}
                    </div>
                </div>
                <div class="form-group form-group-name">
                    <label for="name">Название</label>
                    <input type="text" id="name" v-model="newProduct.name" class="form-control" :class="{ 'is-invalid': errors.name }"  required />
                    <div v-if="errors.name" class="invalid-feedback">
                        {{ errors.name[0] }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="status">Статус</label>
                    <select v-model="newProduct.status" required>
                        <option value="available">Доступен</option>
                        <option value="unavailable">Не доступен</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="form-group-title">Атрибуты</div>
                    <div v-for="(attribute, index) in newProduct.attributes" :key="index" class="attribute-row">
                        <div class="attribute-block">
                            <label for="name">Название</label>
                            <input
                                v-model="attribute.name"
                                type="text"
                                class="attribute-name"
                            />
                        </div>
                        <div class="attribute-block">
                            <label for="text">Значение</label>
                            <input
                                v-model="attribute.value"
                                type="text"
                                class="attribute-value"
                            />
                        </div>
                        <button type="button" @click="removeAttribute(index)" class="remove-attribute"></button>
                    </div>
                    <button class="form-group-attribute-btn" type="button" @click="addAttribute">Добавить атрибут</button>
                </div>

                <div class="form-actions">
                    <button class="form-actions-btn" type="submit">Добавить</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            show: false,
            newProduct: {
                article: '',
                name: '',
                status: 'available',
                attributes: []
            },
            errors: {},
        };
    },
    methods: {
        openPopup() {
            this.errors = {};
            this.show = true;
        },
        closePopup() {
            this.show = false;
            this.resetForm();
            this.errors = {};
        },
        addAttribute() {
            this.newProduct.attributes.push({ name: '', value: '' });
        },
        removeAttribute(index) {
            this.newProduct.attributes.splice(index, 1);
        },
        submitProduct() {
            this.newProduct.data = JSON.stringify(this.newProduct.attributes);

            axios.post('/api/products', this.newProduct)
                .then((response) => {
                    this.$emit('productAdded', response.data);
                    this.closePopup();
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error('Ошибка при добавлении продукта:', error.response.data);
                    }
                });
        },
        resetForm() {
            this.newProduct = {
                article: '',
                name: '',
                status: 'available',
                attributes: []
            };
        }
    }
};
</script>

<style scoped>
.popup-overlay {
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

.popup-content {
    background: #374050;
    padding: 27px 12px;
    border-radius: 5px;
    width: 630px;
    position: relative;
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-header-title {
    font-size: 20px;
    font-weight: 700;
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

form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 13px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 9px;
    line-height: 11px;
    color: #fff;
}

.form-group input,
.form-group select {
    width: 472px;
    height: 30px;
    padding: 8px;
    border-radius: 5px;
    font-size: 11px;
    line-height: 11px;
    font-weight: 300;
}

input:focus, select:focus {
    outline: none;
}

select {
    cursor: pointer;
}

.form-group-title {
    font-size: 14px;
    line-height: 11px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 13px;
}

.form-group-attribute-btn {
    margin-top: 13px;
    color: #5FC6F1;
    border-bottom: 0.5px dashed #5FC6F1;
    font-size: 9px;
    line-height: 11px;
    padding-left: 10px;
    position: relative;
}

.form-group-attribute-btn::after {
    content: '+';
    position: absolute;
    left: 0;
    top: 0;
    font-size: 9px;
    color: #5FC6F1;
}


.form-group .attribute-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    width: 472px;
}

.attribute-row:nth-child(n+2) {
    margin-top: 13px;
}

.attribute-block {
    width: 236px;
}

.attribute-block input {
    width: 100%;
}

.remove-attribute {
    background-image: url("/public/images/Vector (Stroke).svg");
    background-size: cover;
    background-position: center;
    width: 10px;
    height: 11px;
    margin-top: 20px;
    cursor: pointer;
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

.invalid-feedback {
    margin-top: 3px;
    padding: 5px 10px;
    width: 380px;
    background-color: #cbd5e0;
    color: #e3342f;
    font-size: 11px;
    border-radius: 20px;
}
</style>
