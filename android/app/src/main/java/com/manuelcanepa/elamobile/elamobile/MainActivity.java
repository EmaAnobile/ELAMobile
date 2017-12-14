package com.manuelcanepa.elamobile.elamobile;

import android.os.Build;
import android.os.Bundle;
import android.speech.tts.TextToSpeech;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.GridLayout;
import android.widget.LinearLayout;
import android.widget.TextView;

public class MainActivity extends AppCompatActivity
        // implements NavigationView.OnNavigationItemSelectedListener
{
    private TextToSpeech tts;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        //setSupportActionBar(toolbar);

        //FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        //fab.setOnClickListener(new View.OnClickListener() {
        //    @Override
        //    public void onClick(View view) {
        //        Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
        //                .setAction("Action", null).show();
        //    }
        //});

        //DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        //ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawer, toolbar, R.string.navigation_drawer_open, R.string.navigation_drawer_close);
        //drawer.setDrawerListener(toggle);
        //toggle.syncState();

        //NavigationView navigationView = (NavigationView) findViewById(R.id.nav_view);
        //navigationView.setNavigationItemSelectedListener(this);

        this.tts = new TextToSpeech(getApplicationContext(), new TextToSpeech.OnInitListener() {
            @Override
            public void onInit(int status) {
                tts.setPitch(1.3f);
                tts.setSpeechRate(1f);
            }
        });
    }

    @Override
    protected void onDestroy() {
        tts.stop();
        tts.shutdown();
        super.onDestroy();
    }


    //@Override
    //public void onBackPressed() {
    //    DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
    //    if (drawer.isDrawerOpen(GravityCompat.START)) {
    //        drawer.closeDrawer(GravityCompat.START);
    //    } else {
    //        super.onBackPressed();
    //    }
    //}

    //@Override
    //public boolean onCreateOptionsMenu(Menu menu) {
    //    // Inflate the menu; this adds items to the action bar if it is present.
    //    getMenuInflater().inflate(R.menu.main, menu);
    //    return true;
    //}

    //@Override
    //public boolean onOptionsItemSelected(MenuItem item) {
    //    // Handle action bar item clicks here. The action bar will
    //    // automatically handle clicks on the Home/Up button, so long
    //    // as you specify a parent activity in AndroidManifest.xml.
    //    int id = item.getItemId();

    //    //noinspection SimplifiableIfStatement
    //    if (id == R.id.action_settings) {
    //        return true;
    //    }

    //    return super.onOptionsItemSelected(item);
    //}

    //@SuppressWarnings("StatementWithEmptyBody")
    //@Override
    //public boolean onNavigationItemSelected(MenuItem item) {
    //    // Handle navigation view item clicks here.
    //    int id = item.getItemId();

    //    if (id == R.id.nav_camera) {
    //        // Handle the camera action
    //    } else if (id == R.id.nav_gallery) {

    //    } else if (id == R.id.nav_slideshow) {

    //    } else if (id == R.id.nav_manage) {

    //    } else if (id == R.id.nav_share) {

    //    } else if (id == R.id.nav_send) {

    //    }

    //    DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
    //    drawer.closeDrawer(GravityCompat.START);
    //    return true;
    //}

    public void clickEnGrupo(View view) {
        GridLayout grupo = (GridLayout) view;

        final int childCount = grupo.getChildCount();
        LinearLayout bloqueLetras = ((LinearLayout) findViewById(R.id.tablero_bloque_letras));

        for (int i = 0; i < childCount; i++) {
            TextView textView = (TextView) grupo.getChildAt(i);
            String letra = textView.getText().toString();
            int idLetra = this.getResources().getIdentifier(String.format("letra_%s", i + 1), "id", getPackageName());
            TextView bloqueLetra = (TextView) bloqueLetras.findViewById(idLetra);
            bloqueLetra.setText(letra);
        }

        ((LinearLayout) findViewById(R.id.tablero_bloque_grupos)).setVisibility(View.GONE);
        bloqueLetras.setVisibility(View.VISIBLE);
    }

    public void clickEnLetra(View view) {
        TextView letra = (TextView) view;
        EditText textoVoz = ((EditText) findViewById(R.id.texto_voz));
        textoVoz.setText(textoVoz.getText().toString().concat(letra.getText().toString()));

        ((LinearLayout) findViewById(R.id.tablero_bloque_grupos)).setVisibility(View.VISIBLE);
        ((LinearLayout) findViewById(R.id.tablero_bloque_letras)).setVisibility(View.GONE);
    }

    public void reproducirTexto(View view) {
        EditText texto = (EditText) findViewById(R.id.texto_voz);

        if (Build.VERSION.SDK_INT >= 21) {
            CharSequence _texto = (CharSequence) texto.getText();
            tts.speak(_texto, TextToSpeech.QUEUE_FLUSH, null, null);
        } else {
            tts.speak(String.valueOf(texto.getText()), TextToSpeech.QUEUE_FLUSH, null);
        }
    }

    public void clickEnEspacio(View view) {
        EditText textoVoz = ((EditText) findViewById(R.id.texto_voz));
        textoVoz.setText(textoVoz.getText().toString().concat(" "));
    }

    public void clickEnBorrar(View view) {
        EditText textoVoz = ((EditText) findViewById(R.id.texto_voz));
        if(textoVoz.getText().length() == 0)
            return;
        textoVoz.setText(textoVoz.getText().subSequence(0, textoVoz.getText().length() - 1));
    }

    public void clickEnBorrarTodo(View view) {
        EditText textoVoz = ((EditText) findViewById(R.id.texto_voz));
        textoVoz.setText("");
    }
}
