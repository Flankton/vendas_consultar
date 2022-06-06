<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class VendasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Retorna nome do cliente e documento a partir do número da venda.
     * @param  Request  $request
     * @return ResponseJSON
     */
    public function vendas(Request $request)
    {

    $this->validate($request, [
        'nu_venda' => 'required|string',
    ]);
    $numeroVenda = $request->nu_venda;

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api-sandbox.fpay.me/vendas",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{\n\t\n\t\"nu_venda\":\"$numeroVenda\"\n\t\n}",
    CURLOPT_HTTPHEADER => [
        "Client-Code: FC-SB-15",
        "Client-key: 6ea297bc5e294666f6738e1d48fa63d2",
        "Content-Type: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $data = json_decode($response, true);
    $nomeCliente = $data["data"][0]["nm_cliente"];
    $numeroDocumento = $data["data"][0]["nu_documento"];

    $dataResponse = [
        "nomeCliente" => $nomeCliente,
        "numeroDocumento" => $numeroDocumento
    ];

    if ($err) {
    return response()->json(['error' => 'Erro no servidor'], 500, [$err]);
    }
    return response()->json($dataResponse, 200);

}

 /**
  * Retorna parcelas da venda a partir do invalo de datas inseridas e filtros de tipo de pagamento (credito, debito, boleto, dinheiro)
  * e status do pagamento (pago, pendente, cancelado).
  * @param  Request  $request
  * @return ResponseJSON
  */
public function parcelasVendas(Request $request)
{
    $this->validate($request,
        ['dt_inicial' => 'string', 'dt_final' => 'string', 'status' => 'string|in:pago,pendente,cancelado', 'tipo' => 'string|in:credito,debito,boleto,dinheiro']
    );

    $dataInicio = $request->dt_inicial;
    $dataFinal = $request->dt_final;
    $statusPagamento = $request->status;
    $tipoPagamento = $request->tipo;
    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api-sandbox.fpay.me/parcelas",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => "{\n\"per_page\":2,\n\"dt_inicial\":\"$dataInicio\",\n\"dt_final\":\"$dataFinal\",\n\t\"status\": \"$statusPagamento\",\n\"tipo\":\"$tipoPagamento\"\n}",
    CURLOPT_HTTPHEADER => [
        "Client-Code: FC-SB-15",
        "Client-key: 6ea297bc5e294666f6738e1d48fa63d2",
        "Content-Type: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
    $data = json_decode($response, true);
    if ($err) {
    return response()->json(['error' => 'Erro no servidor'], 500, [$err]);
    }
    return response()->json($data, 200);

}

}
