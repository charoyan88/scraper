<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <ul class="list-group shadow">
                    <li v-for="product in products" class="list-group-item">
                        <div class="media align-items-lg-center flex-column flex-lg-row p-3">
                            <div class="media-body order-2 order-lg-1">
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <h4>{{product.title}}</h4>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mt-1">
                                    <h6 class="font-weight-bold my-2">{{product.price}}</h6>
                                    <ul class="list-inline small">
                                        <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                                        <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                                        <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                                        <li class="list-inline-item m-0"><i class="fa fa-star text-success"></i></li>
                                        <li class="list-inline-item m-0"><i class="fa fa-star-o text-gray"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <img :src="product.image_url" alt="Generic placeholder image" width="200" class="ml-lg-5 order-1 order-lg-2">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                base_url: `${process.env.MIX_APP_URL}/api/v1`,
                products:[],


            }
        },
        methods: {},
        created() {
            let url = this.base_url + '/products';
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
                    this.products = data.data;

                }
            }).catch((err) => {
                console.log(err)
            });
        }
    }
</script>
