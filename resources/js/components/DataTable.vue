<template>
    <div v-loading="loading_submit">
        <div class="row ">
            <div class="col-md-12 col-lg-12 col-xl-12 ">
                <div class="row" v-if="applyFilter">
                    
                    <div class="col-lg-3 col-md-4 col-sm-12 pb-2">
                        
                            <el-input placeholder="Buscar"
                                v-model="search.value"
                                style="width: 100%;"
                                prefix-icon="el-icon-search"
                                @input="getRecords">
                            </el-input>
                       
                    </div>
                </div>

            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <slot name="heading"></slot>
                        </thead>
                        <tbody>
                            <slot
                                v-for="(row, index) in records"
                                :row="row"
                               :index="customIndex(index)"
                            ></slot>
                        </tbody>
                    </table>
                    <div>
                        <el-pagination
                            @current-change="getRecords"
                            layout="total, prev, pager, next"
                            :total="total"
                            :current-page.sync="current_page"
                            :page-size='25'
                           
                        >
                        </el-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import queryString from "query-string";

export default {
    props: {
        resource: String,
        applyFilter: {
            type: Boolean,
            default: true,
            required: false
        }
    },
    data() {
        return {
            search: {
               
                value: null
            },
           
            records: [],
            per_page:'',
            current_page:'',
            total:'',
            loading_submit: false
        };
    },
    computed: {},
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getRecords();
        });
    },
    async mounted() {
        //  let column_resource = _.split(this.resource, "/");
        //     console.log(column_resource)
        // await axios.get(`/api/${_.head(column_resource)}/columns`)
        //     .then(response => {
        //         this.columns = response.data;
              // this.search.column = {nombre_difu: "Nombre"};
        //     });
        await this.getRecords();
    },
    methods: {
        customIndex(index) {
            return (
                this.per_page * (this.current_page - 1) +
                index +
                1
            );
        },
        getRecords() {
            this.loading_submit = true;
            return axios.get(`api/${this.resource}s/mostrar?${this.getQueryParameters()}`)
                .then(response => {
                    console.log(response.data.total);
                    this.records = response.data.data;
                    this.total = response.data.total;
                    this.current_page = response.data.current_page
                    this.per_page = response.data.per_page;
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.current_page,
                limit: this.limit,
                ...this.search
            });
        },
        changeClearInput() {
            this.search.value = "";
            this.getRecords();
        }
    }
};
</script>
