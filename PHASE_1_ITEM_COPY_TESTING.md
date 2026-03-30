# Phase 1 Implementation - Item Copy Feature Testing Guide

## Overview
Successfully implemented 3 new API endpoints for copying items between penawaran with historical price tracking and DSS integration.

## API Endpoints

### 1. Copy Items from Penawaran
**Endpoint:** `POST /api/penawaran/copy-items`
**Route Name:** `penawaran.api.copy-items`
**Authorization:** Admin only

#### Request Parameters
```json
{
  "source_penawaran_id": 1,
  "target_penawaran_id": 2,
  "price_strategy": "keep",
  "override_prices": [
    {
      "item_id": 5,
      "harga_asli": 15000,
      "persentase_margin": 12
    }
  ]
}
```

#### Price Strategies
| Strategy | Description | Use Case |
|----------|-------------|----------|
| `keep` | Keep original price from source penawaran | Exact quote for same project |
| `latest` | Use current material master price | Update prices to current market rate |
| `average` | Use average price from material history | Fair market price from history |
| `override` | Use custom prices for each item | Manual adjustments needed |

#### Response Success (200)
```json
{
  "success": true,
  "message": "5 item berhasil disalin dengan strategi keep",
  "data": {
    "target_penawaran_id": 2,
    "items_copied": [
      {
        "id": 10,
        "nama": "Batu Bata",
        "jumlah": 50000,
        "harga_asli": 2000,
        "harga_jual": 2200,
        "strategy_used": "keep"
      }
    ],
    "totals": {
      "total_biaya": 100000000,
      "total_margin": 10000000,
      "ppn": 12100000,
      "grand_total_with_ppn": 122100000
    },
    "price_strategy": "keep"
  }
}
```

#### Response Error (422)
```json
{
  "success": false,
  "message": "Penawaran sumber tidak memiliki item"
}
```

---

### 2. Get Item Price Trend
**Endpoint:** `GET /api/penawaran/item-price-trend`
**Route Name:** `penawaran.api.price-trend`
**Authorization:** Admin & Manager

#### Query Parameters
```
?material_id=5&limit=10
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Riwayat harga berhasil diambil",
  "data": {
    "material_id": 5,
    "material_kode": "MAT001",
    "material_nama": "Batu Bata",
    "current_price": 2100,
    "history": [
      {
        "harga_asli": 2100,
        "persentase_margin": 10,
        "harga_jual": 2310,
        "date": "2026-03-30"
      },
      {
        "harga_asli": 2000,
        "persentase_margin": 10,
        "harga_jual": 2200,
        "date": "2026-03-20"
      }
    ],
    "stats": {
      "avg_price": 2050,
      "min_price": 1900,
      "max_price": 2200,
      "latest_price": 2100,
      "avg_margin": 10.5,
      "price_change_percent": 2.44,
      "trend": "increasing",
      "records_count": 10
    }
  }
}
```

#### Response No History (200)
```json
{
  "success": true,
  "message": "Belum ada riwayat harga untuk material ini",
  "data": {
    "material_id": 5,
    "material_kode": "MAT001",
    "material_nama": "Batu Bata",
    "history": [],
    "stats": null
  }
}
```

---

### 3. Find Similar Penawaran
**Endpoint:** `GET /api/penawaran/similar`
**Route Name:** `penawaran.api.similar`
**Authorization:** Admin & Manager

#### Query Parameters
```
?client_id=1&limit=5&exclude_penawaran_id=2
```

#### Response Success (200)
```json
{
  "success": true,
  "message": "Ditemukan 3 penawaran sebelumnya",
  "data": {
    "client_id": 1,
    "penawaran": [
      {
        "id": 1,
        "no_penawaran": "OFF-2026-001",
        "tanggal": "2026-03-15",
        "status": "disetujui",
        "items_count": 5,
        "grand_total_with_ppn": 122100000,
        "total_biaya": 100000000,
        "total_margin": 10000000,
        "user_note": "Proyek kantor gedung utama"
      },
      {
        "id": 3,
        "no_penawaran": "OFF-2026-003",
        "tanggal": "2026-03-10",
        "status": "disetujui",
        "items_count": 8,
        "grand_total_with_ppn": 200000000,
        "total_biaya": 165000000,
        "total_margin": 20000000,
        "user_note": null
      }
    ]
  }
}
```

---

## Testing Scenarios

### Scenario 1: Basic Copy (Keep Strategy)
**Goal:** Copy all items from previous penawaran with exact same pricing

```bash
curl -X POST http://localhost:8000/api/penawaran/copy-items \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "source_penawaran_id": 1,
    "target_penawaran_id": 2,
    "price_strategy": "keep"
  }'
```

**Expected Result:**
- All items from source copied to target
- Prices unchanged
- Totals recalculated correctly

---

### Scenario 2: Copy with Latest Price Update
**Goal:** Copy items but update prices to current material master rates

```bash
curl -X POST http://localhost:8000/api/penawaran/copy-items \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "source_penawaran_id": 1,
    "target_penawaran_id": 2,
    "price_strategy": "latest"
  }'
```

**Expected Result:**
- Items copied from source
- Prices updated to current material master prices
- Margins preserved from source

---

### Scenario 3: Copy with Average Price Strategy
**Goal:** Copy items using average price from historical data

```bash
curl -X POST http://localhost:8000/api/penawaran/copy-items \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "source_penawaran_id": 1,
    "target_penawaran_id": 2,
    "price_strategy": "average"
  }'
```

**Expected Result:**
- Items copied with prices as average of approved penawaran history
- More stable pricing than 'latest'
- Better reflects market trends

---

### Scenario 4: Copy with Override Prices
**Goal:** Selective price adjustments for certain items

```bash
curl -X POST http://localhost:8000/api/penawaran/copy-items \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TOKEN" \
  -d '{
    "source_penawaran_id": 1,
    "target_penawaran_id": 2,
    "price_strategy": "override",
    "override_prices": [
      {
        "item_id": 5,
        "harga_asli": 18000,
        "persentase_margin": 15
      },
      {
        "item_id": 6,
        "harga_asli": 35000,
        "persentase_margin": 12
      }
    ]
  }'
```

**Expected Result:**
- Item 5 & 6 use custom prices
- Other items use original prices from source
- All margins properly calculated

---

### Scenario 5: View Price Trend for Decision Making
**Goal:** Analyze price history before copying

```bash
curl GET http://localhost:8000/api/penawaran/item-price-trend?material_id=5&limit=15 \
  -H "Authorization: Bearer TOKEN"
```

**Expected Result:**
- Historical prices shown
- Trend direction identified (increasing/decreasing/stable)
- Average, min, max prices displayed
- Can inform override decisions

---

### Scenario 6: Find Similar Previous Penawaran
**Goal:** Locate previous projects from same client to copy from

```bash
curl GET http://localhost:8000/api/penawaran/similar?client_id=1&limit=5 \
  -H "Authorization: Bearer TOKEN"
```

**Expected Result:**
- List of approved penawaran for client
- Sorted by recency
- Item counts and totals shown for quick review

---

## Integration with DSS

The copied items automatically integrate with DSS through:

1. **Historical Price Factor** - DSS analysis considers:
   - Average prices from history
   - Price trend direction (increasing/decreasing)
   - Price volatility for risk adjustment

2. **Risk Calculation Enhancement** - DSS now includes:
   - Price change percentage in risk score
   - Historical overrun patterns
   - Material-specific risk factors

Example DSS analysis with copied items:
```json
{
  "risk_level": "Sedang",
  "risk_score": 45.2,
  "price_trend_factor": 1.05,
  "historical_overrun_rate": 12.5,
  "recommendation": "Risiko sedang. Harga material menunjukkan tren naik 5.2%. Pertimbangkan review estimasi."
}
```

---

## Testing Checklist

- [ ] Scenario 1: Basic copy (keep strategy)
- [ ] Scenario 2: Copy with latest prices
- [ ] Scenario 3: Copy with average prices
- [ ] Scenario 4: Copy with override prices
- [ ] Scenario 5: View price trends
- [ ] Scenario 6: Find similar penawaran
- [ ] Verify DSS analysis uses copied items history
- [ ] Check audit logs (dss_decisions channel)
- [ ] Verify grand total calculations are correct
- [ ] Verify PPN (11%) calculation
- [ ] Test with large item counts (>100 items)
- [ ] Test error handling (invalid IDs, authorization)
- [ ] Verify timestamps are created correctly
- [ ] Confirm audit trails are logged

---

## Database Queries for Manual Verification

### Check Copied Items
```sql
SELECT p.id, p.no_penawaran, COUNT(ip.id) as item_count, p.grand_total_with_ppn
FROM penawaran p
LEFT JOIN item_penawaran ip ON p.id = ip.penawaran_id
WHERE p.id IN (2, 3, 4)
GROUP BY p.id;
```

### View Price History for Material
```sql
SELECT ip.harga_asli, ip.persentase_margin, ip.harga_jual, p.status, ip.created_at
FROM item_penawaran ip
JOIN penawaran p ON ip.penawaran_id = p.id
WHERE ip.material_id = 5 AND p.status = 'disetujui'
ORDER BY ip.created_at DESC
LIMIT 15;
```

### Check DSS Decisions Log
```bash
tail -f storage/logs/dss_decisions.log
```

---

## Next Steps

**Week 2 (UI Implementation):**
1. Create "Copy from Previous" button in penawaran create form
2. Modal to select source penawaran and price strategy
3. Visual price trend chart component
4. Strategy selector with helpful descriptions
5. Confirm & review before applying copy

**Enhancement Ideas:**
- Smart material matching (fuzzy match by name/description)
- AI-powered price recommendation based on trends
- Bulk copy multiple penawaran at once
- Copy templates for common project types
- A/B testing different strategies

