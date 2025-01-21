type timeUnit = 
    | 'minute' 
    | 'hour'
    | 'day'
    | 'week' 
    | 'month'
    | 'quarter'
    | 'year';

declare namespace CordovaPlugins {
    interface LocalNotification {
        schedule(options: LocalNotificationOptions | LocalNotificationOptions[]): void;
        cancel(id: number | number[], callback?: Function): void;
        cancelAll(callback?: Function): void;
        hasPermission(callback: (granted: boolean) => void): void;
        requestPermission(callback: (granted: boolean) => void): void;
    }

    interface LocalNotificationOptions {
        id?: number;
        title?: string;
        text?: string;
        trigger?: Trigger;
        smallIcon?: string;
        icon?: string;
        foreground?: boolean;
        sound?: string;
    }

    interface Trigger {
        in?: number;
        unit?: "second" | timeUnit
        at?: Date;
        every?: timeUnit | { 
            year?: number;
            month?: number;
            day?: number;
            hour?: number;
            minute?: number
        };
        count?: number;
    }
}

declare var cordova: {
    plugins: {
        notification: {
            local: CordovaPlugins.LocalNotification;
        };
    };
};