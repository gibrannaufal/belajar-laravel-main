
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Penjualan Menu</title>

    <style>
        *{
            font-family: 'Poppins', sans-serif;
        }
        table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}
            table td {
				padding: 5px;
				vertical-align: top;
			}
            table tr td:nth-child(2) {
				text-align: right;
			}
            table tr td:nth-child(2) {
				text-align: right;
			}
            table tr.top table td {
				padding-bottom: 20px;
			}
            table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}
            table tr.heading td {
				background: #2c8da2;
				border: 1px solid #eee;
				font-weight: bold;
                color: white
			}
            table tr.total td{
                background: #eee;
				font-weight: bold;
            }
            table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}
            table tr.item td {
			}
            .text-center{
                text-align: center
            }
    </style>
</head>
<body>
    @php
    $hariArray = range(1, $data['jmlHari']);
    @endphp
    <table>
        <thead>
            
            <tr class="heading">
                <td class="text-center" colspan="{{count($hariArray)+2}}">LAPORAN PENJUALAN MENU</td>
            </tr>
            <tr class="heading">
                <td rowspan="2" class="text-center">Menu</td>
                <td class="text-center" colspan="{{count($hariArray)}}" >Periode:  {{ date('F Y', strtotime($data['bulan'].'-01')) }} </td>
                <td rowspan="2" class="text-center">Total</td>
            </tr>
            <tr class="heading">
                @foreach ($hariArray as $hari)
                    <td class="text-center">{{$loop->iteration}}</td>
                @endforeach
            </tr>
        </thead>
        <tbody> 
            <tr style="background:#eee; color:#2e2e2e"> 
                <td>
                 Grand Total
                </td>
          
                @foreach ($hariArray as $hari)
                <td>
                    @foreach ($data['total'] as $category => $items)
                        @foreach ( $items[0] as $total => $totalsum)
                            @if(date('d', strtotime(htmlspecialchars($totalsum['tanggal']))) == $hari )
                                     {{ 'Rp. ' . number_format( htmlspecialchars($totalsum['total']) , 0, ',', '.') }}
                            @endif
                        @endforeach
                    @endforeach
                </td>
                @endforeach
                <td>
                    @foreach ($data['total'] as $category => $items)
                          {{ 'Rp. ' . number_format( $items[1], 0, ',', '.') }}
                    @endforeach
                </td>
            </tr>
            @foreach ($data['content'] as $category => $items)
            @if ($category === 'Food')
                <tr style="background:#eee; color:#2e2e2e">
                    <td colspan="{{ count($hariArray) + 2 }}">{{ $category }}</td>
                </tr>
                @foreach ($items as $item)
                <tr> 
                    <td> 
                        {{ $item['nama'] }} 
                    </td>
                    @foreach ($hariArray as $hari)
                    <td> 
                        @foreach ($item['tanggal'] as $date => $value)
                            @if ( date('d', strtotime($date))  == $hari)
                             {{ 'Rp. ' . number_format( $value, 0, ',', '.') }}
                            @endif
                        @endforeach
                    </td>
                    @endforeach
                    <td>  
                        {{ 'Rp. ' . number_format( $item['totalsum'], 0, ',', '.') }}
                    </td>
                    
                </tr>
                @endforeach
            @endif
            @endforeach
            @foreach ($data['content'] as $category => $items)
            @if ($category === 'Drink')
                <tr style="background:#eee; color:#2e2e2e">
                    <td colspan="{{ count($hariArray) + 2 }}">{{ $category }}</td>
                </tr>
                @foreach ($items as $item)
                <tr> 
                    <td> 
                        {{ $item['nama'] }} 
                    </td>
                    @foreach ($hariArray as $hari)
                    <td> 
                        @foreach ($item['tanggal'] as $date => $value)
                            @if ( date('d', strtotime($date))  == $hari)
                            {{ 'Rp. ' . number_format( $value, 0, ',', '.') }}
                            @endif
                        @endforeach
                    </td>
                    @endforeach
                    <td>  
                        {{ 'Rp. ' . number_format( $item['totalsum'], 0, ',', '.') }}
                    </td>
                    
                </tr>
                @endforeach
            @endif
            @endforeach
            @foreach ($data['content'] as $category => $items)
            @if ($category === 'Snack')
                <tr style="background:#eee; color:#2e2e2e">
                    <td colspan="{{ count($hariArray) + 2 }}">{{ $category }}</td>
                </tr>
                @foreach ($items as $item)
                <tr> 
                    <td> 
                        {{ $item['nama'] }} 
                    </td>
                    @foreach ($hariArray as $hari)
                    <td> 
                        @foreach ($item['tanggal'] as $date => $value)
                            @if ( date('d', strtotime($date))  == $hari)
                            {{ 'Rp. ' . number_format( $value, 0, ',', '.') }}
                            @endif
                        @endforeach
                    </td>
                    @endforeach
                    <td>  
                        {{ 'Rp. ' . number_format( $item['totalsum'], 0, ',', '.') }}
                    </td>
                    
                </tr>
                @endforeach
            @endif
            @endforeach
        
        
        </tbody>
    </table>
      
</body>
</html>