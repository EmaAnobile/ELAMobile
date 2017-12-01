package com.manuelcanepa.elamobile.elamobile.helpers;

import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import org.json.JSONException;
import org.json.JSONObject;

import cz.msebera.android.httpclient.Header;

public class UsuariosService extends HttpUtils {
    private Boolean respuesta = false;

    public Boolean ValidarCredenciales(String mEmail, String mPassword) {
        RequestParams params = new RequestParams();
        params.add("usuario", mEmail);
        params.add("password", mPassword);

        final UsuariosService self = this;

        this.post("/usuarios/acceder", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                self.procesarHeaders(headers);

                try {
                    String json = new String(responseBody);

                    JSONObject jsonObj = new JSONObject(json);

                    String estado = jsonObj.get("status").toString();

                    self.respuesta = (estado != "0") ? true : false;

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                self.procesarHeaders(headers);
            }
        });

        return respuesta;

    }
}
