<template>
    <div class="container">
        <div v-if="error_msg" class="alert alert-danger mt-2" role="alert">
            {{error_msg}}
        </div>
        <div class="row h-75 justify-content-center align-items-center">
            <div class="form-group col-md-4">
                <label for="inputKeyword">Find Products</label>
                <input type="text" class="form-control" id="inputKeyword" @keyup="changeKeyword" v-model="keyword" placeholder="Enter Keyword">
            </div>
            <div class="form-group col-md-3">
                <label for="selectMarket">Select Market</label>
                <select type="text" class="form-control" id="selectMarket" v-model="selected">
                    <option v-for="market in markets" v-bind:value="market.name">{{market.name}}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button v-if="isLoading" class="btn btn-primary  mt-3" type="button" disabled>
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button v-else-if="save_button" class="btn btn-primary btn-block mt-3" @click="save">Save</button>
                <button v-else="" class="btn btn-primary btn-block mt-3" @click="find">Find</button>
            </div>
            <div v-if="products_count" class="form-group col-md-9">
                <label for="inputKeyword">Found {{products_count}} Products For {{selected}} Shop</label>
            </div>
            <div v-if="saved" class="form-group col-md-9">
                <label for="inputKeyword">{{success_msg}}</label>
            </div>
        </div>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                base_url: `${process.env.MIX_APP_URL}/api/v1`,
                keyword: "",
                isLoading: false,
                markets: '',
                error_msg: "",
                success_msg : "",
                save_button: false,
                saved: false,
                products_count: "",
                images: [],
                descriptions: [],
                prices: [],
                selected: "Amazon"

            }
        },
        methods: {
            find(){
                this.saved = false;
                let keyword = this.keyword;
                let url = new URL(this.base_url + '/find/products'),
                    params = {
                        keyword: keyword,
                    };
                Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
                fetch(url, {
                    'Content-Type': 'application/json'
                }).then((res) => res.text())
                    .then((data) => {
                        data = JSON.parse(data);
                        if (data.status === "Error") {
                            this.error_msg = data.msg;
                        } else {
                            let html_data = data.data;
                            let parser = new DOMParser();
                            let wrapper = parser.parseFromString(html_data, "text/html");
                            let products_html = wrapper.querySelectorAll('.s-search-results .s-result-item');
                            let products = [];
                            let images = [];
                            let prices = [];
                            let descriptions = [];

                            if (products_html.length) {
                                for (const product of products_html) {
                                    images.push(product.querySelector('.s-image').getAttribute('src'));
                                    if (product.querySelector('.a-offscreen') != null) {
                                        prices.push(product.querySelector('.a-offscreen').innerText);
                                    } else {
                                        prices.push('');
                                    }
                                    if (product.querySelector('.a-text-normal') != null) {

                                        descriptions.push(product.querySelector('.a-text-normal').innerText.replace(/[\n]+/gm, ""));
                                    } else {
                                        descriptions.push('');
                                    }
                                }
                                this.images = images;
                                this.prices = prices;
                                this.descriptions = descriptions;
                            }
                            this.save_button = true;
                            this.products_count = prices.length;
                        }
                    }).catch((err) => {
                    console.log(err)
                });

            },
            save(){
                if (Object.keys(this.images).length>0 && Object.keys(this.prices).length>0 && Object.keys(this.descriptions).length>0) {
                    this.isLoading = true;
                    this.products_count = "";
                    let images = this.images;
                    let prices = this.prices;
                    let descriptions = this.descriptions;
                    let url = this.base_url + '/products';
                    fetch(url, {
                        method: 'POST',
                        body: JSON.stringify({images: images,prices:prices,descriptions:descriptions}),
                        headers: {
                            'Content-Type': 'application/json'
                        }

                    }).then((res) => res.text())
                        .then((data) => {
                            this.isLoading = false;
                            data = JSON.parse(data);
                            if (data.status === "Error") {
                                this.error_msg = data.msg;
                                console.log(this.error_msg)
                            } else {
                                this.success_msg = data.msg;
                                this.save_button = false;
                                this.saved = true;
                            }
                        })
                        .catch((err) => {
                            console.log(err)
                        });
                }
            },

            changeKeyword(){
                this.save_button = false;
                this.saved = false;
                this. products_count = "";
            }
        },
        created() {
            let url = this.base_url + '/market';
            fetch(url, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then((res) => res.text()
            ).then((data) => {
                data = JSON.parse(data);
                if (data.status === "Error") {
                    this.error_msg = data.msg;
                } else {
                    this.markets = data.data;

                }
            }).catch((err) => {
                console.log(err)
            });
        },
        components: {
            //
        }
    }
</script>
