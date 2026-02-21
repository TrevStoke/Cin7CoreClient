<?php

namespace Trevweb\Cin7CoreClient;

class Cin7CoreEndpoints
{
// --- Reference Books & Settings ---
    const ATTRIBUTE_SET = 'ref/attributeset';
    const BANK_ACCOUNTS = 'ref/account/bank';
    const BRAND = 'ref/brand';
    const CARRIER = 'ref/carrier';
    const CHART_OF_ACCOUNTS = 'ref/account';
    const CUSTOMER_DEFAULT_TEMPLATE = 'ref/customer/templates';
    const CUSTOMER_CREDITS = 'ref/customer/credits';
    const FIXED_ASSET_TYPE = 'ref/fixedassettype';
    const LOCATION = 'ref/location';
    const PAYMENT_TERM = 'ref/paymentterm';
    const PRICE_TIER = 'ref/priceTier';
    const PRODUCT_CATEGORY = 'ref/category';
    const SHIPPING_ZONES = 'reference/shipZones';
    const SHIPPING_ZONES_ENABLED = 'reference/shipZonesEnabled';
    const TAX_RULE = 'ref/tax';
    const TEMPLATES = 'ref/templates';
    const UNIT_OF_MEASURE = 'ref/unit';
    const USER_ME = 'me';
    const USER_ME_ADDRESSES = 'me/addresses';
    const USER_ME_CONTACTS = 'me/contacts';

    // --- Product & Inventory ---
    const PRODUCT = 'product'; // Supports Pagination
    const PRODUCT_ATTACHMENTS = 'product/attachments';
    const PRODUCT_AVAILABILITY = 'ref/productavailability';
    const PRODUCT_FAMILY = 'productFamily';
    const PRODUCT_FAMILY_ATTACHMENTS = 'productFamily/attachments';
    const PRODUCT_SUPPLIERS = 'product-suppliers';
    const CUSTOM_PRICES = 'custom-prices';
    const MARKUP_PRICES = 'product/markupprices';
    const DEALS = 'reference/deals';
    const PRODUCT_DISCOUNTS = 'reference/discount';

    // --- Stock Operations ---
    const STOCK_ADJUSTMENT_LIST = 'stockadjustmentList'; // Supports Pagination
    const STOCK_ADJUSTMENT = 'stockadjustment';
    const STOCK_TAKE_LIST = 'stockTakeList'; // Supports Pagination
    const STOCK_TAKE = 'stocktake';
    const STOCK_TRANSFER_LIST = 'stockTransferList'; // Supports Pagination
    const STOCK_TRANSFER = 'stockTransfer';
    const STOCK_TRANSFER_ORDER = 'stockTransfer/order';
    const INVENTORY_WRITE_OFF_LIST = 'inventoryWriteOffList';
    const INVENTORY_WRITE_OFF = 'inventoryWriteOff';

    // --- Sales ---
    const SALE_LIST = 'saleList'; // Supports Pagination
    const SALE = 'sale';
    const SALE_QUOTE = 'sale/quote';
    const SALE_ORDER = 'sale/order';
    const SALE_FULFILMENT = 'sale/fulfilment';
    const SALE_FULFILMENT_PICK = 'sale/fulfilment/pick';
    const SALE_FULFILMENT_PACK = 'sale/fulfilment/pack';
    const SALE_FULFILMENT_SHIP = 'sale/fulfilment/ship';
    const SALE_INVOICE = 'sale/invoice';
    const SALE_PAYMENT = 'sale/payment';
    const SALE_CREDIT_NOTE_LIST = 'saleCreditNoteList';
    const SALE_CREDIT_NOTE = 'sale/creditnote';
    const SALE_MANUAL_JOURNAL = 'sale/manualJournal';
    const SALE_ATTACHMENT = 'sale/attachment';

    // --- Purchases ---
    const PURCHASE_LIST = 'purchaseList'; // Supports Pagination
    const PURCHASE = 'purchase';
    const PURCHASE_ORDER = 'purchase/order';
    const PURCHASE_STOCK = 'purchase/stock';
    const PURCHASE_INVOICE = 'purchase/invoice';
    const PURCHASE_PAYMENT = 'purchase/payment';
    const PURCHASE_CREDIT_NOTE_LIST = 'purchaseCreditNoteList';
    const PURCHASE_CREDIT_NOTE = 'purchase/creditnote';
    const PURCHASE_MANUAL_JOURNAL = 'purchase/manualJournal';
    const PURCHASE_ATTACHMENT = 'purchase/attachment';

    // --- Advanced Purchases ---
    const ADVANCED_PURCHASE = 'advanced-purchase';
    const ADVANCED_PURCHASE_STOCK = 'advanced-purchase/stock';
    const ADVANCED_PURCHASE_PUT_AWAY = 'advanced-purchase/put-away';
    const ADVANCED_PURCHASE_INVOICE = 'advanced-purchase/invoice';
    const ADVANCED_PURCHASE_CREDIT = 'advanced-purchase/creditnote';
    const ADVANCED_PURCHASE_PAYMENT = 'advanced-purchase/payment';
    const ADVANCED_PURCHASE_JOURNAL = 'advanced-purchase/manualJournal';

    // --- Manufacturing / Production ---
    const DISASSEMBLY_LIST = 'disassemblyList';
    const DISASSEMBLY = 'disassembly';
    const DISASSEMBLY_ORDER = 'disassembly/order';
    const FINISHED_GOODS_LIST = 'finishedGoodsList';
    const FINISHED_GOODS = 'finishedGoods';
    const FINISHED_GOODS_ORDER = 'finishedGoods/order';
    const FINISHED_GOODS_PICK = 'finishedGoods/pick';
    const FACTORY_CALENDAR = 'production/factoryCalendar';
    const PRODUCTION_BOM = 'production/productionBOM';
    const PRODUCTION_ORDER_LIST = 'production/orderList';
    const PRODUCTION_ORDER = 'production/order';
    const PRODUCTION_RUN = 'production/order/run';
    const WORK_CENTERS = 'production/workcenters';
    const RESOURCES_LIST = 'production/resourceList';
    const RESOURCE = 'production/resource';
    const SUSPEND_REASON = 'production/suspendReason';

    // --- CRM ---
    const CUSTOMER = 'customer';
    const SUPPLIER = 'supplier';
    const SUPPLIER_DEPOSITS = 'ref/supplier/deposits';
    const LEAD = 'crm/lead';
    const OPPORTUNITY = 'crm/opportunity';
    const CRM_TASK = 'crm/task';
    const CRM_TASK_CATEGORY = 'crm/taskcategory';
    const CRM_WORKFLOW = 'crm/workflow';
    const CRM_WORKFLOW_START = 'crm/workflowstart';

    // --- Financials & Other ---
    const MONEY_TASK_LIST = 'moneyTaskList';
    const MONEY_OPERATION = 'moneyOperation';
    const BANK_TRANSFER = 'bankTransfer';
    const JOURNAL = 'journal';
    const TRANSACTIONS = 'transactions';
    const WEBHOOKS = 'webhooks';
}