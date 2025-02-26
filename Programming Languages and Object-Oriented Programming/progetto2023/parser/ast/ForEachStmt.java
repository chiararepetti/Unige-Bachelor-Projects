package progetto2023.parser.ast;

import static java.util.Objects.requireNonNull;

import progetto2023.visitors.Visitor;

/*
 * l'esecuzione dello statement 'foreach' IDENT 'in' Exp Block consiste nella valutazione dell'espressione Exp rispetto 
 * all'ambiente corrente env; deve essere restituito un vettore v sui cui elementi viene iterata l'esecuzione di Block 
 * rispetto a un ambiente ottenuto da env aggiungendo uno scope annidato contenente la sola variabile IDENT alla quale 
 * viene assegnato a ogni iterazione un elemento di v in ordine dall'indice minimo al massimo. Inizialmente la variabile  
 * IDENT viene inizializzata con un valore intero arbitrario.
 */

public class ForEachStmt implements Stmt {
    private final Variable var; // non-optional field
	private final Exp exp; // non-optional field
	private final Block block; // non-optional field

	public ForEachStmt(Variable var, Exp exp, Block block) {
		this.exp = requireNonNull(exp);
		this.var = requireNonNull(var);
		this.block = requireNonNull(block);
	}

	@Override
	public String toString() {
		return getClass().getSimpleName() + "(" + var + "," + exp + "," + block + ")";
	}

	@Override
	public <T> T accept(Visitor<T> visitor) {
		return visitor.visitForEachStmt(var, exp, block);
	}
}