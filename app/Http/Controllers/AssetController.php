<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\StoreAssetRequest;

use App\Asset;
use App\TransactionChartAccount;
class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::user()->can('asset-module'))
      {
        return view('asset.index');
      }else{
        return view('403');
      }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if(\Auth::user()->can('create-asset-module'))
      {
          $asset = Asset::all();
          $code_fix = '';
          if(count($asset) > 0)
          {
              $code_asset = Asset::all()->max('id');
              $sub_str = $code_asset+1;
              $code_fix = 'AST-'.$sub_str;
          }else
          {
              $code_asset = count($asset)+1;
              $code_fix = 'AST-'.$code_asset;
          }
        return view('asset.create')
          ->with('asset',$code_fix);
      }else{
        return view('403');
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetRequest $request)
    {
        $asset = New Asset;
        $asset->code = $request->code;
        $asset->name = $request->name;
        $asset->date_purchase = $request->date_purchase;
        $asset->amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));
        $asset->residual_value = floatval(preg_replace('#[^0-9.]#','',$request->residual_value));
        $asset->periode = $request->periode;
        $asset->notes = $request->notes;
        $asset->save();

        $asset_id = $asset->id;

        $asset_account = New TransactionChartAccount;
        $asset_account->amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));
        $asset_account->sub_chart_account_id = $request->asset_account;
        $asset_account->created_at = date('Y-m-d h:i:s');
        $asset_account->updated_at = date('Y-m-d h:i:s');
        $asset_account->reference = $asset_id;
        $asset_account->source = 'asset';
        $asset_account->type = 'masuk';
        $asset_account->description = $request->name;
        $asset_account->memo = $request->notes;
        $asset_account->save();

        // penyusutan garis lurus
        $biaya_count = (floatval(preg_replace('#[^0-9.]#','',$request->amount))-floatval(preg_replace('#[^0-9.]#','',$request->residual_value)))/($request->periode/12);

        $biaya_penyusutan_account = New TransactionChartAccount;
        $biaya_penyusutan_account->amount = $biaya_count;
        $biaya_penyusutan_account->sub_chart_account_id = $request->biaya_penyusutan_account;
        $biaya_penyusutan_account->created_at = date('Y-m-d h:i:s');
        $biaya_penyusutan_account->updated_at = date('Y-m-d h:i:s');
        $biaya_penyusutan_account->reference = $asset_id;
        $biaya_penyusutan_account->source = $request->date_purchase;
        $biaya_penyusutan_account->type = 'masuk';
        $biaya_penyusutan_account->description = $request->name;
        $biaya_penyusutan_account->memo = 'BIAYA PENYUSUTAN';
        $biaya_penyusutan_account->save();

        $akumulasi_penyusutan_account = New TransactionChartAccount;
        $akumulasi_penyusutan_account->amount = $biaya_count;
        $akumulasi_penyusutan_account->sub_chart_account_id =  $request->akumulasi_penyusutan_account;
        $akumulasi_penyusutan_account->created_at = date('Y-m-d h:i:s');
        $akumulasi_penyusutan_account->updated_at = date('Y-m-d h:i:s');
        $akumulasi_penyusutan_account->reference = $asset_id;
        $akumulasi_penyusutan_account->source = $request->date_purchase;
        $akumulasi_penyusutan_account->type = 'masuk';
        $akumulasi_penyusutan_account->description = $request->name;
        $akumulasi_penyusutan_account->memo = 'AKUMULASI PENYUSUTAN';
        $akumulasi_penyusutan_account->save();


        return redirect('asset')
            ->with('successMessage','Asset has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $asset = Asset::findOrFail($id);
        return view('asset.show')
          ->with('asset',$asset);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if(\Auth::user()->can('edit-asset-module'))
      {
          $asset = Asset::findOrFail($id);
          return view('asset.edit')
              ->with('asset', $asset);
      }else{
          return view('403');
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $asset = Asset::findOrFail($id);
      $asset->code = $request->code;
      $asset->name = $request->name;
      $asset->date_purchase = $request->date_purchase;
      $asset->amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));
      $asset->residual_value = floatval(preg_replace('#[^0-9.]#','',$request->residual_value));
      $asset->periode = $request->periode;
      $asset->notes = $request->notes;
      $asset->save();
      $asset_id = $asset->id;

      \DB::table('transaction_chart_accounts')->where('reference',$asset_id)->where('description',$request->notes_old)->delete();
      //exit();
      $asset_account = New TransactionChartAccount;
      $asset_account->amount = floatval(preg_replace('#[^0-9.]#','',$request->amount));
      $asset_account->sub_chart_account_id = $request->asset_account;
      $asset_account->created_at = date('Y-m-d h:i:s');
      $asset_account->updated_at = date('Y-m-d h:i:s');
      $asset_account->reference = $asset_id;
      $asset_account->source = 'asset';
      $asset_account->type = 'masuk';
      $asset_account->description = $request->name;
      $asset_account->memo = $request->notes;
      $asset_account->save();

      // penyusutan garis lurus
      $biaya_count = (floatval(preg_replace('#[^0-9.]#','',$request->amount))-floatval(preg_replace('#[^0-9.]#','',$request->residual_value)))/($request->periode/12);

      $biaya_penyusutan_account = New TransactionChartAccount;
      $biaya_penyusutan_account->amount = $biaya_count;
      $biaya_penyusutan_account->sub_chart_account_id = $request->biaya_penyusutan_account;
      $biaya_penyusutan_account->created_at = date('Y-m-d h:i:s');
      $biaya_penyusutan_account->updated_at = date('Y-m-d h:i:s');
      $biaya_penyusutan_account->reference = $asset_id;
      $biaya_penyusutan_account->source = $request->date_purchase;
      $biaya_penyusutan_account->type = 'masuk';
      $biaya_penyusutan_account->description = $request->name;
      $biaya_penyusutan_account->memo = 'BIAYA PENYUSUTAN';
      $biaya_penyusutan_account->save();

      $akumulasi_penyusutan_account = New TransactionChartAccount;
      $akumulasi_penyusutan_account->amount = $biaya_count;
      $akumulasi_penyusutan_account->sub_chart_account_id =  $request->akumulasi_penyusutan_account;
      $akumulasi_penyusutan_account->created_at = date('Y-m-d h:i:s');
      $akumulasi_penyusutan_account->updated_at = date('Y-m-d h:i:s');
      $akumulasi_penyusutan_account->reference = $asset_id;
      $akumulasi_penyusutan_account->source = $request->date_purchase;
      $akumulasi_penyusutan_account->type = 'masuk';
      $akumulasi_penyusutan_account->description = $request->name;
      $akumulasi_penyusutan_account->memo = 'AKUMULASI PENYUSUTAN';
      $akumulasi_penyusutan_account->save();

      return redirect('asset')
        ->with('successMessage','Asset has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $asset = Asset::findOrFail($request->asset_id);
        $asset->delete();

        \DB::table('transaction_chart_accounts')->where('reference',$request->asset_id)->where('description',$request->asset_description)->delete();

        return redirect('asset')
          ->with('successMessage',"Asset $asset->name has been deleted");
    }
}
