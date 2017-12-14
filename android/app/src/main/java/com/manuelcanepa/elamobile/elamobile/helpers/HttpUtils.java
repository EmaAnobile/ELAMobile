package com.manuelcanepa.elamobile.elamobile.helpers;

import android.content.Context;
import android.os.Looper;
import android.util.Log;

import com.loopj.android.http.AsyncHttpClient;
import com.loopj.android.http.AsyncHttpResponseHandler;
import com.loopj.android.http.PersistentCookieStore;
import com.loopj.android.http.RequestParams;
import com.loopj.android.http.SyncHttpClient;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

import cz.msebera.android.httpclient.Header;

public class HttpUtils {
    //private static final String BASE_URL = "http://emaela.manuelcanepa.com.ar/api";
    private static final String BASE_URL = "http://192.168.1.85/api";

    public static String cookieRequest = "";

    public static AsyncHttpClient syncHttpClient = new SyncHttpClient();
    public static AsyncHttpClient asyncHttpClient = new AsyncHttpClient();

    public void SetCookieStorage(PersistentCookieStore myCookieStore){
        syncHttpClient.setCookieStore(myCookieStore);
        asyncHttpClient.setCookieStore(myCookieStore);
    }

    public static void get(String url, RequestParams params, AsyncHttpResponseHandler responseHandler) {
        responseHandler = HttpUtils.parseResponseHandler(responseHandler);
        getClient().get(getAbsoluteUrl(url), params, responseHandler);
    }

    public static AsyncHttpResponseHandler post(String url, RequestParams params, AsyncHttpResponseHandler responseHandler) {
        responseHandler = HttpUtils.parseResponseHandler(responseHandler);
        getClient().post(getAbsoluteUrl(url), params, responseHandler);
        return responseHandler;
    }

    private static String getAbsoluteUrl(String relativeUrl) {
        return BASE_URL + relativeUrl;
    }

    public static void procesarHeaders(Header[] headers) {
        // TODO: 22/11/2017 buscar si viene la cabecera de cookie y guardarla
        Pattern p = Pattern.compile("[\\s]*(PHPSESSID=[^\\s;]+)");
        for (Header header : headers) {
            String cabecera = header.getName().toLowerCase();
            if (cabecera.equals("set-cookie")) {
                String valor = header.getValue();
                Matcher matcher = p.matcher(valor);
                if (matcher.find()) {
                    cookieRequest = matcher.group(1);
                    break;
                }
            }
        }
    }

    private static AsyncHttpClient getClient() {
        // Return the synchronous HTTP client when the thread is not prepared
        if (Looper.myLooper() == null)
            return syncHttpClient;

        return asyncHttpClient;
    }

    public static AsyncHttpResponseHandler parseResponseHandler(AsyncHttpResponseHandler responseHandler) {
        // Siempre y cuando tengamos cookies de sesion guardadas:
        // Si viene nulo, crear un nuevo response handler con la cabecera de cookie
        // Si no viene nulo, validar Header de cookie

        return responseHandler;
    }
}