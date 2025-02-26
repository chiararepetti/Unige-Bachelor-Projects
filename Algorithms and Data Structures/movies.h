#include <string>
#include "list.h"

namespace movies {
    // struttura da definire; potete cambiare nome secondo la vostra implementazione
    struct VertexNode;

    typedef VertexNode *MovieDB;

    typedef std::string Label;

    enum NodeType {PERSON, MOVIE};
    enum EdgeType {ACTED, DIRECTED, PRODUCED};

    const int NO_BACON_NUMBER = -1;

    const MovieDB emptyMovieDB = NULL;

    // crea un database vuoto
    MovieDB createEmpty();

    // aggiunge un nodo di tipo PERSON o MOVIE con nome l, fallisce se un nodo con quell'etichetta è già presente
    // restituisce true se l'inserimento ha avuto successo
    bool addVertex(MovieDB &mdb, const Label l, const NodeType t);

    // aggiunge un arco tra `person` e `movie` con tipo t (ACTED, DIRECTED o PRODUCED)
    // fallisce se i nodi non hanno tipo appropriato o se un arco identico (stessa persona, film e tipo) esiste già
    bool addEdge(MovieDB &mdb, const Label person, const Label movie, const EdgeType t);

    // numero di nodi con tipo t
    int numNodesPerType(const MovieDB &mdb, NodeType t);

    // numero di archi con tipo t
    int numEdgesPerType(const MovieDB &mdb, EdgeType t);

    // lista, senza ripetizioni, di attori che hanno interpretato un film con l'attore con etichetta l
    // restituisce una lista vuota se l non è una persona o non appartiene al grafo
    lista::List coActors(const MovieDB &mdb, Label l);

    // lista, senza ripetizioni, di attori che hanno interpretato un film prodotto da l
    // restituisce una lista vuota se l non è una persona o non appartiene al grafo
    lista::List actorsProducedBy(const MovieDB &mdb, Label l);

    // lista, senza ripetizioni, di attori che hanno interpretato un film diretto da l
    // restituisce una lista vuota se l non è una persona o non appartiene al grafo
    lista::List actorsDirectedBy(const MovieDB &mdb, Label l);

    // numero di Bacon di l (vedi testo); restituisce NO_BACON_NUMBER se l non è una persona o non ha un numero di Bacon
    int BaconNumber(const MovieDB &mdb, Label l);
}

// stampa il contenuto del database; siete liberi di scegliere il formato, ma pensatelo in modo che sia comprensibile per un umano
void printDB(const movies::MovieDB &mdb);
