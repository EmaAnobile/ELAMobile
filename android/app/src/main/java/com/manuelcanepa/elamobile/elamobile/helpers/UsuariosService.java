package com.manuelcanepa.elamobile.elamobile.helpers;

import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.RequestParams;

import cz.msebera.android.httpclient.Header;

public class UsuariosService extends HttpUtils {

    public AsyncHttpResponseHandler ValidarCredenciales(String mEmail, String mPassword) {
        RequestParams params = new RequestParams();
        params.add("usuario", mEmail);
        params.add("password", mPassword);

        final UsuariosService self = this;

        return this.post("/usuarios/acceder", params, new AsyncHttpResponseHandler() {
            @Override
            public void onSuccess(int statusCode, Header[] headers, byte[] responseBody) {
                self.procesarHeaders(headers);
            }

            @Override
            public void onFailure(int statusCode, Header[] headers, byte[] responseBody, Throwable error) {
                self.procesarHeaders(headers);
            }
        });

    }
}
