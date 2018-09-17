<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-20">
                        <h1>System Dashboard</h1>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-default">
                            <div class="card-header">JobList</div>

                            <div class="card-body">

                        <at-progress :percent="50"></at-progress>
                        <at-table :columns="columns1" :data="data1"></at-table>
                        </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <div class="row no-gutter">
            <div class="col-sm-24 col-md-12">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                columns1: [
                    {
                        title: 'Name',
                        key: 'name'
                    },
                    {
                        title: 'Age',
                        key: 'age'
                    },
                    {
                        title: 'Address',
                        key: 'address'
                    }
                ],
                data1: [
                    {
                        name: 'Stephen Curry',
                        age: 18,
                        address: 'Shenzhen Shi, Baoan Qu'
                    },
                    {
                        name: 'Lebron James',
                        age: 25,
                        address: 'Gangding, Tianhe, Guangzhou'
                    },
                    {
                        name: 'Kobe Bryant',
                        age: 24,
                        address: 'Pudong, Shanghai'
                    },
                    {
                        name: 'Kevin Durant',
                        age: 22,
                        address: 'Shen Nan Da Dao, Nanshan Qu, Shenzhen Shi'
                    },
                    {
                        name: 'Russell Westbrook',
                        age: 21,
                        address: 'Chaoyang District, Beijing'
                    },
                    {
                        name: 'Tim Duncan',
                        age: 26,
                        address: 'The Mixc of Shenzhen City '
                    },
                    {
                        name: 'Tony Parker',
                        age: 25,
                        address: 'Shenzhen Book City'
                    },
                    {
                        name: 'Kyrie Irving',
                        age: 20,
                        address: 'Guangzhou Higher Education Mega Center'
                    },
                    {
                        name: 'Isaiah Thomas',
                        age: 19,
                        address: 'Chaoyang District, Beijing'
                    }
                ]
            }
        }
    }
</script>

<style lang="scss">
</style>