/**
 * This module is an abstract class to store informations in the database
 * It uses this cordova plugin: {@link https://github.com/storesafe/cordova-sqlite-storage}
 * 
 * __file:__ `db.ts` \
 * __date:__ 19/01/2025 (fr)
 * @author Elie Carrot
 * @packageDocumentation
 */

/**
 * This class is a basic interface to exchange more easly
 * with the database
 */
export abstract class DataBase {
    protected db: SQLitePlugin.Database | null;

    /**
     * open the database and that's all
     */
    constructor() {
        // create db object
        this.db = window.sqlitePlugin.openDatabase({
            name: 'melancometre.db',
            location: 'default',
            androidDatabaseProvider: 'system',
            androidLockWorkaround: 1
        });
    }

    /**
     * Reopens the database after it was closed.
     */
    public reopen(): void {
        // nothing to do if already setted
        if (this.db !== null) return;

        // else open DB
        this.db = window.sqlitePlugin.openDatabase({
            name: 'melancometre.db',
            location: 'default',
            androidDatabaseProvider: 'system',
            androidLockWorkaround: 1
        });
    }

    /**
     * Creates the database (if not exists !)
     * @returns a void promise
     */
    public abstract create(): Promise<void>;

    /**
     * Get all data from the database
     * @returns
     */
    public abstract getAll(): Promise<unknown[]>;

    /**
     * Add a data to the database
     * @param d the data to add
     * @returns a void promise
     */
    public abstract add(d: unknown): Promise<void>;

    /**
     * delete all data from the database
     * @returns a void promise
     */
    public abstract deleteAll(): Promise<void>;

    /**
     * Close the database
     * @returns a void promise
     */
    public close(): Promise<void> {
        return new Promise((resolve, reject) => {
            // if already close
            if (this.db === null) return resolve();

            // else close DB
            this.db.close(() => { this.db = null; resolve(); }, reject);
        });
    }
}
