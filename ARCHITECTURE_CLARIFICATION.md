# Architecture Clarification: Budget Tracking vs Inventory

**Date**: March 30, 2026  
**Status**: CLEANED UP ✅

## TL;DR

| Component | Status | Used? | Reason |
|-----------|--------|-------|--------|
| **Inventory Management** | ❌ REMOVED | No | Business model: Budget tracking, not stock reduction |
| **Supplier Master** | ✅ KEPT | YES | Required for Material import & vendor management |
| **Penawaran (BoQ)** | ✅ KEPT | YES | Core to budget tracking system |
| **Item Copy APIs** | ✅ READY | YES | Phase 1 feature: Copy with 4 price strategies |

---

## What Changed & Why

### ❌ Inventory REMOVED (Cleanup Complete)

**Files Deleted:**
- `tests/Feature/CriticalFixesTest.php` (tested deprecated inventory logic)
- `tests/Unit/PenawaranWorkflowTest.php` (old workflow tests)

**Files Modified:**
- `routes/web.php`: Removed InventoryController import
- `resources/views/layouts/sidebar.blade.php`: Removed Inventory menu link
- `app/Http/Controllers/PenawaranController.php`: Removed Inventory & LogInventory imports

**Code Left Behind (Documentation):**
- Comments in PenawaranController explain WHY inventory was removed
- Model & migration files remain (for possible future goods receipt module)

---

## ✅ Supplier KEPT (Still Used)

**Why Supplier is Required:**

1. **Material Import** - MaterialImport.php auto-creates suppliers when importing BOQ files:
   ```php
   // MaterialImport.php line 147-162
   if ($kategori === Material::TYPE_BARANG && !empty($supplier_name)) {
       $supplier = Supplier::where('nama', $supplier_name)->first();
       if (!$supplier) {
           $supplier = Supplier::create([...]);
       }
       $supplier_id = $supplier?->id;
   }
   ```

2. **Material Model** - Foreign key relationship:
   ```php
   // Material.php
   public function supplier()
   {
       return $this->belongsTo(Supplier::class);
   }
   ```

3. **Vendor Management** - Admin needs supplier master data accessible
   - Supplier sidebar link: ✅ STAYS
   - SupplierController routes: ✅ STAYS

---

## What System Actually Does

### Current Flow (Budget Tracking Model)

```
User creates Penawaran (Quotation/BoQ)
   ↓
System calculates budget: total_biaya + margin + PPN (11%)
   ↓
Penawaran approved → grand_total_with_ppn = Budget reference
   ↓
Project starts → track actual spending in Pengeluaran
   ↓
Compare Pengeluaran vs Penawaran budget → determine overrun/savings
```

### NOT a Stock Reduction System

✅ What it does:
- Budget tracking and comparison
- Quotation management with item copy
- Cost estimation and validation
- Project expense tracking

❌ What it does NOT do:
- Reduce warehouse stock on quotation approval
- Track physical inventory levels
- Manage goods receipt from supplier
- Trigger procurement workflows

---

## Future: Goods Receipt Module

**When warehouse/procurement is added later**, inventory tracking CAN be implemented:

```
1. Purchase Order (PO) created → Supplier order
2. Goods Receipt (GR) → Supplier delivers → Stock increases
3. Item usage → Track against actual consumption (if needed)
4. Inventory reconciliation → Annual physical count
```

Currently: FUTURE SCOPE (not part of Phase 2)

---

## Consequences of Cleanup

### ✅ Positive
- Cleaner codebase (removed dead code)
- Reduced confusion (no inventory routes that don't work)
- Sidebar reflects actual system capabilities
- Clear business model documentation

### ⚠️ None Negative
- All Phase 1 features still work (API endpoints unchanged)
- Phase 2 (Python AI) unaffected
- No database schema changes required
- Supplier → still available for future use

---

## Next Steps

**You are at this point:**
```
Phase 1: ✅ DONE (Item copy APIs working)
Phase 2: 🔵 CHOOSE
   A) Python LR/MA setup & testing
   B) Week 2 UI implementation  
   C) Both in parallel
```

**Choose option A/B/C, then we proceed.**
