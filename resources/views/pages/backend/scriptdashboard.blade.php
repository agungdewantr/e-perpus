@push('scripts')
    <script>
        $(document).ready(function () {
            lineChart();
            barChart();
            pieChart();
            // $("#barChart").hide();
            // $("#pieChart").hide();

            // $('.filterGrafik').click(function(){
            //     var target = $(this).data("target");
            //     if(target == 'kunjunganPerpustakaan'){
            //         $("#lineChart").show();
            //         $("#barChart").hide();
            //         $("#pieChart").hide();
            //     }
            //     else if(target == 'anggotaPerpustakaan') {
            //         $("#lineChart").hide();
            //         $("#barChart").show();
            //         $("#pieChart").hide();
            //     }
            //     else if(target == 'koleksiBuku'){
            //         $("#lineChart").hide();
            //         $("#barChart").hide();
            //         $("#pieChart").show();
            //     }
            // });
        });
        function lineChart() {
            $.ajax({
                url: "{{route('dashboard.get.linechart')}}",
                success: function(data){
                    var bulan = data.map(function(item){
                        return item.bulan;
                    });
                    var jumlahKunjungan = data.map(function(item){
                        return item.jumlahKunjungan;
                    });
                    var jumlahKunjuganLakiLaki = data.map(function(item){
                        return item.jumlahKunjuganLakiLaki;
                    });
                    var jumlahKunjuganPerempuan = data.map(function(item){
                        return item.jumlahKunjuganPerempuan;
                    });
                    dom = document.getElementById("lineChart"),
                    myChart = echarts.init(dom),
                    option = {
                        title: {
                            text: 'Kunjungan Perpustakaan'
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        legend: {
                        },
                        grid: {
                            left: '1%',
                            right: '1%',
                            bottom: '10%',
                            top: '20%',
                            containLabel: true
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            },
                            top: 'top',
                        },
                        xAxis: {
                            type: 'category',
                            boundaryGap: false,
                            data: bulan
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                name: 'Laki-laki',
                                type: 'line',
                                color: ["#005eff", "#ccc"],
                                data: jumlahKunjuganLakiLaki
                            },
                            {
                                name: 'Perempuan',
                                type: 'line',
                                color: ["#ff0072", "#ccc"],
                                data: jumlahKunjuganPerempuan
                            },
                            {
                                name: 'Jumlah',
                                type: 'line',
                                color: ["#00ff06", "#ccc"],
                                data: jumlahKunjungan
                            },
                        ],
                        legend: {
                            left: 'right',
                            top: '10%',
                        },
                    };
                    option && myChart.setOption(option);
                },
            });
        }
        function barChart(){
            $.ajax({
                url: "{{route('dashboard.get.barchart')}}",
                success: function(data) {
                    // console.log(data);
                    dom = document.getElementById("barChart"),
                    myChart = echarts.init(dom),
                    option = {
                        title: {
                            text: 'Peminjam Buku'
                        },
                        legend: {
                            left: 'right',
                            top: '10%',
                        },
                        toolbox: {
                            feature: {
                                saveAsImage: {}
                            },
                            top: 'top',
                        },
                        tooltip: {},
                        grid: {
                            left: '1%',
                            right: '1%',
                            bottom: '10%',
                            containLabel: true
                        },
                        dataset: {
                            source: data
                        },
                        xAxis: { type: 'category' },
                        yAxis: {},
                        series: [
                                    {
                                        type: 'bar',
                                        itemStyle: {
                                            color: '#205E61'
                                        }
                                    },
                                    {
                                        type: 'bar',
                                        itemStyle: {
                                            color: '#8DCBE6'
                                        }
                                    },
                                ]
                    };
                    option && myChart.setOption(option);
                }
            });
        }
        function pieChart(){
            $.ajax({
                url: "{{ route('dashboard.get.piechart') }}",
                success: function(data){
                    var nama = data.map(function(item) {
                        return item.name;
                    });
                    var value = data.map(function(item) {
                        return item.value;
                    });

                    dom = document.getElementById("pieChart"),
                    myChart = echarts.init(dom),
                    option = {
                        title: {
                            text: 'Koleksi Buku'
                        },
                        legend: {
                            orient: 'horizontal',
                            bottom: '0',
                            data: data,
                            formatter: function (name, value) {
                                // Ambil nilai dari array 'value' berdasarkan indeks
                                // Gabungkan nama, nilai, dan teks " Buku"
                                return name ;
                            },
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                saveAsImage: { show: true }
                            }
                        },
                        tooltip: {
                            trigger: 'item',
                            formatter: function(params) {
                                var bukuValue = params.value || 0;
                                return params.name + ' - ' + bukuValue + ' Buku';
                            },
                        },
                        series: [{
                            name: 'Nightingale Chart',
                            type: 'pie',
                            radius: ['10%', '50%'],
                            center: ['50%', '35%'],
                            roseType: 'area',
                            itemStyle: {
                                borderRadius: 5
                            },
                            data: data
                        }]
                    };
                    option && myChart.setOption(option);
                },
            });
        }

    </script>
@endpush
